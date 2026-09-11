<?php

namespace Tests\Feature;

use App\Http\Middleware\EnsureRole;
use App\Http\Middleware\OfflineDesktopAuth;
use App\Models\Exam;
use App\Models\ExamAnswer;
use App\Models\ExamSession;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use App\Services\ExamGradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ExamEngineIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;
    protected Exam $utme;
    protected Subject $math;
    protected Subject $english;

    protected function setUp(): void
    {
        parent::setUp();

        Role::updateOrCreate(['name' => 'user', 'guard_name' => 'web']);
        Role::updateOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::updateOrCreate(['name' => 'moderator', 'guard_name' => 'web']);

        $this->student = User::factory()->create([
            'email' => 'candidate@cbtwise.com',
            'email_verified_at' => now(),
            'plan' => 'premium',
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

        $this->english = Subject::create([
            'exam_id' => $this->utme->id,
            'name' => 'English Language',
            'slug' => 'english-language',
            'is_active' => true,
        ]);
    }

    public function test_question_answers_are_hidden_from_default_array_and_json_serialization(): void
    {
        $q = Question::create([
            'exam_id' => $this->utme->id,
            'subject_id' => $this->math->id,
            'question_text' => 'What is 2 + 2?',
            'option_a' => '3',
            'option_b' => '4',
            'option_c' => '5',
            'option_d' => '6',
            'correct_option' => 'b',
            'explanation' => 'Basic arithmetic',
            'dedupe_hash' => hash('sha256', 'What is 2 + 2?'),
        ]);

        $array = $q->toArray();
        $this->assertArrayNotHasKey('correct_option', $array, 'correct_option must NOT be present in default array serialization');
        $this->assertArrayNotHasKey('explanation', $array, 'explanation must NOT be present in default array serialization');

        $json = json_encode($q);
        $this->assertStringNotContainsString('correct_option', $json);
        $this->assertStringNotContainsString('Basic arithmetic', $json);
    }

    public function test_exam_grading_service_computes_accurate_utme_score_and_breakdown(): void
    {
        $q1 = Question::create([
            'exam_id' => $this->utme->id,
            'subject_id' => $this->math->id,
            'question_text' => 'Math Q1',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'a',
            'dedupe_hash' => hash('sha256', 'Math Q1'),
        ]);

        $q2 = Question::create([
            'exam_id' => $this->utme->id,
            'subject_id' => $this->english->id,
            'question_text' => 'English Q1',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'correct_option' => 'c',
            'dedupe_hash' => hash('sha256', 'English Q1'),
        ]);

        $session = ExamSession::create([
            'user_id' => $this->student->id,
            'exam_id' => $this->utme->id,
            'mode' => 'mock',
            'subjects' => [$this->math->id, $this->english->id],
            'total_questions' => 2,
            'duration_seconds' => 3600,
            'started_at' => now(),
            'status' => 'in_progress',
        ]);

        ExamAnswer::create([
            'exam_session_id' => $session->id,
            'question_id' => $q1->id,
            'selected_option' => 'a', // Correct
        ]);

        ExamAnswer::create([
            'exam_session_id' => $session->id,
            'question_id' => $q2->id,
            'selected_option' => 'd', // Incorrect
        ]);

        $service = new ExamGradingService();
        $graded = $service->grade($session);

        $this->assertEquals('submitted', $graded->status);
        $this->assertEquals(1, $graded->correct_count);
        // UTME scaled to 400: (1 / 2) * 400 = 200
        $this->assertEquals(200.0, (float) $graded->score);

        $this->assertIsArray($graded->score_breakdown);
        $this->assertArrayHasKey($this->math->id, $graded->score_breakdown);
        $this->assertEquals(1, $graded->score_breakdown[$this->math->id]['correct']);
        $this->assertEquals(100.0, $graded->score_breakdown[$this->math->id]['percentage']);

        $this->assertArrayHasKey($this->english->id, $graded->score_breakdown);
        $this->assertEquals(0, $graded->score_breakdown[$this->english->id]['correct']);
        $this->assertEquals(0.0, $graded->score_breakdown[$this->english->id]['percentage']);

        // Check question stats incremented
        $q1Fresh = $q1->fresh();
        $this->assertEquals(1, $q1Fresh->times_correct);

        // Test idempotency: calling grade again must NOT double-increment or change scores
        $secondGraded = $service->grade($graded);
        $this->assertEquals(1, $secondGraded->correct_count);
        $this->assertEquals(200.0, (float) $secondGraded->score);
        $this->assertEquals(1, $q1Fresh->fresh()->times_correct);
    }

    public function test_ensure_role_middleware_accepts_pipe_delimited_roles(): void
    {
        $moderator = User::factory()->create(['email' => 'mod@cbtwise.com']);
        $moderator->assignRole('moderator');

        $middleware = new EnsureRole();
        $request = Request::create('/admin/reports', 'GET');

        // Test authorized user with pipe-delimited role string
        Auth::login($moderator);
        $response = $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'admin|moderator|support');

        $this->assertEquals(200, $response->getStatusCode());

        // Test unauthorized standard user
        Auth::login($this->student);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        }, 'admin|moderator|support');
    }

    public function test_offline_auth_middleware_blocks_external_host_header_privilege_escalation(): void
    {
        Config::set('app.env', 'production');
        Config::set('app.offline_desktop', false);

        $middleware = new OfflineDesktopAuth();

        // Attacker sends request with spoofed Host header and external IP
        $request = Request::create('https://cbtwise.com.ng/dashboard', 'GET', [], [], [], [
            'HTTP_HOST' => '127.0.0.1',
            'REMOTE_ADDR' => '198.51.100.25', // External IP
        ]);

        Auth::logout();
        $this->assertFalse(Auth::check());

        $middleware->handle($request, function ($req) {
            return new Response('OK', 200);
        });

        // Ensure attacker is NOT logged in
        $this->assertFalse(Auth::check(), 'OfflineDesktopAuth must NEVER authenticate external requests in production');
    }
}
