<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Exam;
use App\Models\Subject;
use App\Models\Question;
use App\Models\Bookmark;
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
}