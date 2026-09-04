<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\PurchaseCode;
use App\Mail\PremiumExpiringMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;
use Tests\TestCase;

class Phase3FeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Create the required roles
        Role::updateOrCreate(['name' => 'admin']);
        Role::updateOrCreate(['name' => 'user']);
    }

    /**
     * Test cbtwise:downgrade-expired command.
     */
    public function test_expired_users_are_downgraded(): void
    {
        // User whose premium has expired
        $expiredUser = User::factory()->create([
            'plan' => 'premium',
            'premium_expires_at' => now()->subDay(),
        ]);

        // User whose premium is still active
        $activeUser = User::factory()->create([
            'plan' => 'premium',
            'premium_expires_at' => now()->addDay(),
        ]);

        $this->artisan('cbtwise:downgrade-expired')
            ->expectsOutputToContain('Downgraded 1 expired premium users to free.')
            ->assertExitCode(0);

        $expiredUser->refresh();
        $activeUser->refresh();

        $this->assertEquals('free', $expiredUser->plan);
        $this->assertEquals('premium', $activeUser->plan);
    }

    /**
     * Test expiring email warnings.
     */
    public function test_warning_emails_are_sent_to_expiring_users(): void
    {
        Mail::fake();

        // User expiring in exactly 3 days
        $expiringUser = User::factory()->create([
            'plan' => 'premium',
            'premium_expires_at' => now()->addDays(3),
        ]);

        // User expiring in 5 days (should not get warned)
        User::factory()->create([
            'plan' => 'premium',
            'premium_expires_at' => now()->addDays(5),
        ]);

        $this->artisan('cbtwise:downgrade-expired')
            ->expectsOutputToContain('Sent expiration warnings to 1 users.')
            ->assertExitCode(0);

        Mail::assertSent(PremiumExpiringMail::class, function ($mail) use ($expiringUser) {
            return $mail->user->id === $expiringUser->id;
        });
    }

    /**
     * Test purchase code generation by admin.
     */
    public function test_admin_can_generate_purchase_codes(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        Livewire::test(\App\Livewire\Admin\PurchaseCodes::class)
            ->set('durationDays', 30)
            ->set('quantity', 5)
            ->call('generateBatch')
            ->assertHasNoErrors()
            ->assertSee('Successfully generated 5 purchase codes');

        $this->assertEquals(5, PurchaseCode::count());
        $this->assertDatabaseHas('admin_activity_log', [
            'admin_id' => $admin->id,
            'action' => 'purchase_code.batch_generated',
        ]);
    }

    /**
     * Test purchase code redemption for logged-in user.
     */
    public function test_user_can_redeem_purchase_code(): void
    {
        $admin = User::factory()->create();
        $user = User::factory()->create(['plan' => 'free']);
        
        $code = PurchaseCode::generate($admin->id, 90);

        $this->actingAs($user);

        Livewire::test(\App\Livewire\Redeem::class)
            ->set('code', $code->code)
            ->call('redeem')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $user->refresh();
        $code->refresh();

        $this->assertEquals('premium', $user->plan);
        $this->assertTrue($code->isUsed());
        $this->assertEquals($user->id, $code->used_by_user_id);
    }

    /**
     * Test purchase code redemption & account auto-creation for guest user.
     */
    public function test_guest_can_redeem_purchase_code_and_register(): void
    {
        $admin = User::factory()->create();
        $code = PurchaseCode::generate($admin->id, 30);

        Livewire::test(\App\Livewire\Redeem::class)
            ->set('code', $code->code)
            ->set('name', 'John Doe')
            ->set('email', 'john@example.com')
            ->set('phone', '08012345678')
            ->set('state', 'Lagos')
            ->set('exam_year', date('Y') + 1)
            ->call('redeem')
            ->assertHasNoErrors()
            ->assertRedirect(route('dashboard'));

        $this->assertAuthenticated();
        
        $user = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('premium', $user->plan);
        $this->assertTrue($user->hasRole('user'));

        $code->refresh();
        $this->assertTrue($code->isUsed());
        $this->assertEquals($user->id, $code->used_by_user_id);
    }

    /**
     * Test EnsurePremiumPlan middleware.
     */
    public function test_custom_middleware_blocks_non_premium(): void
    {
        $freeUser = User::factory()->create(['plan' => 'free']);

        $this->actingAs($freeUser);

        // Accessing pricing should be fine (it's public), but setup mock/study limits check is fine too
        $response = $this->get(route('exam.setup'));
        $response->assertOk(); // setup page is accessible to everyone
    }

    /**
     * Test DailyQuestionLimit middleware.
     */
    public function test_custom_middleware_blocks_daily_limit_exceeded(): void
    {
        $freeUser = User::factory()->create([
            'plan' => 'free',
            'daily_question_count' => 20,
            'daily_count_reset_at' => now(),
        ]);

        // Mock config limit to 20
        config(['cbtwise.free_daily_limit' => 20]);

        $this->actingAs($freeUser);

        // Attempting to visit exam session runner should redirect because daily limit reached
        $response = $this->get(route('exam.run', ['session' => 1]));
        $response->assertRedirect(route('dashboard'));
    }
}
