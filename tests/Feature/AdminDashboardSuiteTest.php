<?php

namespace Tests\Feature;

use App\Models\Exam;
use App\Models\Affiliate;
use App\Models\PurchaseCode;
use App\Models\Question;
use App\Models\Subject;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AdminDashboardSuiteTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default roles
        Role::updateOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::updateOrCreate(['name' => 'moderator', 'guard_name' => 'web']);
        Role::updateOrCreate(['name' => 'support', 'guard_name' => 'web']);
        Role::updateOrCreate(['name' => 'content_editor', 'guard_name' => 'web']);
        Role::updateOrCreate(['name' => 'analyst', 'guard_name' => 'web']);
        Role::updateOrCreate(['name' => 'user', 'guard_name' => 'web']);
    }

    public function test_guests_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/admin');
        $response->assertRedirect('/login');

        $responseQuestions = $this->get('/admin/questions');
        $responseQuestions->assertRedirect('/login');
    }

    public function test_regular_students_cannot_access_admin_dashboard(): void
    {
        $student = User::factory()->create();
        $student->assignRole('user');

        $response = $this->actingAs($student)->get('/admin');
        $response->assertForbidden();
    }

    public function test_admin_can_access_admin_dashboard_and_modules(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin)->get('/admin')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/dashboard')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/questions')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/exams-subjects')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/users')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/subscriptions')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/analytics')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/messages')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/reports')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/purchase-codes')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/bulk-seeder')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/notifications')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/settings')->assertSuccessful();
        $this->actingAs($admin)->get('/admin/activity-logs')->assertSuccessful();
    }

    public function test_purchase_code_generation_formats_correctly(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $code = PurchaseCode::generate($admin->id, 30, 'Test Student', 'Test Note');

        $this->assertNotNull($code);
        $this->assertStringStartsWith('CBT-', $code->code);
        $this->assertEquals(30, $code->plan_duration_days);
        $this->assertEquals('active', $code->status);
        $this->assertTrue($code->isAvailable());

        $code->disable();
        $this->assertFalse($code->isAvailable());

        $code->restore();
        $this->assertTrue($code->isAvailable());
    }

    public function test_question_deduplication_hashing(): void
    {
        $text1 = "Which of the following elements is a noble gas?";
        $text2 = "  which of the following elements is a noble gas?  ";

        $hash1 = Question::dedupeHash($text1);
        $hash2 = Question::dedupeHash($text2);

        $this->assertEquals($hash1, $hash2);
    }

    public function test_admin_can_add_an_existing_student_as_an_affiliate(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $student = User::factory()->create(['email' => 'affiliate.student@example.com']);

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\Affiliates::class)
            ->call('openCreateModal')
            ->set('affiliateEmail', $student->email)
            ->set('newAffiliateStatus', 'active')
            ->call('createAffiliate')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('affiliates', [
            'user_id' => $student->id,
            'status' => 'active',
        ]);
        $this->assertSame(1, Affiliate::count());
    }
}
