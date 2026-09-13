<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Bookmark;
use App\Models\Topic;
use App\Services\QuestionFetcher;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentExperienceTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected Exam $utme;
    protected Subject $math;

    protected function setUp(): void
    {
        parent::setUp();

        Role::updateOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $this->student = User::factory()->create([
            'email_verified_at' => now(),
        ]);
        $this->student->assignRole('user');

        $this->utme = Exam::create([
            'name' => 'UTME / JAMB',
            'slug' => 'utme',
            'is_active' => true,
        ]);

        $this->math = Subject::create([
            'exam_id' => $this->utme->id,
            'name' => 'Mathematics',
            'slug' => 'mathematics',
            'is_active' => true,
        ]);
    }

    public function test_authenticated_student_can_access_dashboard_and_see_summary_stats(): void
    {
        $response = $this->actingAs($this->student)->get('/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Success is the sum of small efforts');
        $response->assertSee('Tests Taken');
        $response->assertSee('Avg. Score');
        $response->assertSee('Study Streak');
        $response->assertSee('Time Spent');
        $response->assertSee('Continue Practicing');
        $response->assertSee('Take Mock Exam');
    }

    public function test_student_navigation_aliases_render_successfully(): void
    {
        $this->actingAs($this->student);

        $routes = [
            '/practice',
            '/mock-exams',
            '/results',
            '/progress',
            '/bookmarks',
            '/streak',
            '/notifications',
            '/settings',
            '/subscription',
            '/purchase-code',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }
    }

    public function test_bookmarks_filtering_and_display(): void
    {
        $question = Question::create([
            'exam_id' => $this->utme->id,
            'subject_id' => $this->math->id,
            'question_text' => 'What is 5 + 7?',
            'option_a' => '10',
            'option_b' => '12',
            'option_c' => '14',
            'option_d' => '15',
            'correct_option' => 'b',
            'dedupe_hash' => md5('What is 5 + 7?'),
            'is_active' => true,
        ]);

        Bookmark::create([
            'user_id' => $this->student->id,
            'question_id' => $question->id,
        ]);

        $response = $this->actingAs($this->student)->get('/bookmarks');
        $response->assertStatus(200);
        $response->assertSee('What is 5 + 7?');
        $response->assertSee('Mathematics');
    }

    public function test_subscription_page_displays_plan_status(): void
    {
        $response = $this->actingAs($this->student)->get('/subscription');
        $response->assertStatus(200);
        $response->assertSee('Free Tier');
        $response->assertSee('Upgrade to Premium');
    }

    public function test_jamb_course_checker_renders_and_searches_courses(): void
    {
        $response = $this->actingAs($this->student)->get('/jamb-checker');
        $response->assertStatus(200);
        $response->assertSee('JAMB Course and Subject Combination Checker');
        $response->assertSee('Medicine and Surgery (MBBS)');
        $response->assertSee('Computer Science / Information Technology');

        \Livewire\Livewire::actingAs($this->student)
            ->test(\App\Livewire\Tools\CourseChecker::class)
            ->set('search', 'Medicine')
            ->assertSee('Medicine and Surgery (MBBS)')
            ->call('selectCourse', 'medicine-and-surgery')
            ->assertSee('Compulsory JAMB UTME 4-Subject Combination')
            ->assertSee('5 SSCE credit passes');
    }

    public function test_exam_setup_supports_course_presets_and_auto_selects_subjects(): void
    {
        $bio = Subject::create([
            'exam_id' => $this->utme->id,
            'name' => 'Biology',
            'slug' => 'biology',
            'is_active' => true,
        ]);
        $chem = Subject::create([
            'exam_id' => $this->utme->id,
            'name' => 'Chemistry',
            'slug' => 'chemistry',
            'is_active' => true,
        ]);
        $phy = Subject::create([
            'exam_id' => $this->utme->id,
            'name' => 'Physics',
            'slug' => 'physics',
            'is_active' => true,
        ]);
        $eng = Subject::create([
            'exam_id' => $this->utme->id,
            'name' => 'English Language',
            'slug' => 'english-language',
            'is_active' => true,
        ]);

        \Livewire\Livewire::actingAs($this->student)
            ->withQueryParams(['exam' => 'utme', 'course' => 'medicine-and-surgery'])
            ->test(\App\Livewire\Exam\Setup::class)
            ->assertSet('selectedCourse', 'medicine-and-surgery')
            ->assertCount('selectedSubjects', 4)
            ->assertSee('Auto-Select by Career / Target Course');
    }

    public function test_topic_explorer_and_topic_practice_session(): void
    {
        $topic = \App\Models\Topic::create([
            'subject_id' => $this->math->id,
            'name' => 'Calculus: Differentiation & Integration',
            'sort_order' => 1,
        ]);

        $response = $this->actingAs($this->student)->get('/topic-practice');
        $response->assertStatus(200);
        $response->assertSee('Practice By Topic');
        $response->assertSee('Calculus: Differentiation');

        \Livewire\Livewire::actingAs($this->student)
            ->withQueryParams(['exam' => 'utme', 'subject' => $this->math->id, 'topic' => $topic->id])
            ->test(\App\Livewire\Exam\Setup::class)
            ->assertSet('selectedTopicId', $topic->id)
            ->assertSet('currentStep', 4)
            ->assertSee('Practice by Topic (Syllabus)')
            ->call('startExam');

        $session = \App\Models\ExamSession::where('user_id', $this->student->id)->latest()->first();
        $this->assertNotNull($session);
        $this->assertEquals($topic->id, $session->topic_id);
    }

    public function test_topic_practice_uses_same_subject_questions_when_imported_questions_are_untagged(): void
    {
        $topic = Topic::create([
            'subject_id' => $this->math->id,
            'name' => 'Algebra',
            'sort_order' => 1,
        ]);
        $question = Question::create([
            'exam_id' => $this->utme->id,
            'subject_id' => $this->math->id,
            'dedupe_hash' => Question::dedupeHash('Solve 2x = 8.'),
            'question_text' => 'Solve 2x = 8.',
            'option_a' => '2',
            'option_b' => '3',
            'option_c' => '4',
            'option_d' => '5',
            'correct_option' => 'c',
            'source' => 'csv',
        ]);

        $fetcher = app(QuestionFetcher::class);
        $questions = $fetcher->fetch($this->utme, $this->math, 1, null, $topic->id);

        $this->assertTrue($fetcher->lastFetchUsedSubjectFallback);
        $this->assertSame([$question->id], $questions->pluck('id')->all());
    }
}
