<?php

namespace Tests\Feature;

use App\Mail\ReceiptMail;
use App\Models\Payment;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PaystackPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected User $student;

    protected function setUp(): void
    {
        parent::setUp();

        Role::updateOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $this->student = User::factory()->create([
            'email' => 'student@example.com',
            'email_verified_at' => now(),
        ]);
        $this->student->assignRole('user');

        SystemSetting::set('paystack_secret_key', 'sk_test_ready', 'payment', true);
    }

    public function test_authenticated_user_can_initialize_paystack_checkout(): void
    {
        Http::fake([
            'https://api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/test-reference',
                    'reference' => 'PSK_test_reference',
                ],
            ]),
        ]);

        $response = $this->actingAs($this->student)
            ->post(route('payment.initialize'), ['plan_type' => 'monthly']);

        $response->assertRedirect('https://checkout.paystack.com/test-reference');

        $this->assertDatabaseHas('payments', [
            'user_id' => $this->student->id,
            'paystack_reference' => 'PSK_test_reference',
            'amount_kobo' => 150000,
            'status' => 'pending',
            'plan_duration_days' => 30,
            'plan_type' => 'monthly',
        ]);

        Http::assertSent(fn ($request) => $request->hasHeader('Authorization', 'Bearer sk_test_ready')
            && $request['email'] === 'student@example.com'
            && $request['amount'] === 150000);
    }

    public function test_successful_callback_upgrades_the_matching_user(): void
    {
        Mail::fake();

        Payment::create([
            'user_id' => $this->student->id,
            'paystack_reference' => 'PSK_paid_reference',
            'amount_kobo' => 400000,
            'status' => 'pending',
            'plan_duration_days' => 90,
            'plan_type' => 'quarterly',
        ]);

        Http::fake([
            'https://api.paystack.co/transaction/verify/PSK_paid_reference' => Http::response([
                'status' => true,
                'data' => $this->successfulPaystackData('PSK_paid_reference', 400000),
            ]),
        ]);

        $response = $this->get(route('payment.callback', ['reference' => 'PSK_paid_reference']));

        $response->assertRedirect(route('dashboard'));
        $this->assertDatabaseHas('payments', [
            'paystack_reference' => 'PSK_paid_reference',
            'status' => 'success',
        ]);

        $this->student->refresh();
        $this->assertSame('premium', $this->student->plan);
        $this->assertTrue($this->student->premium_expires_at->isFuture());

        Mail::assertSent(ReceiptMail::class);
    }

    public function test_webhook_rejects_invalid_signature(): void
    {
        $payload = json_encode([
            'event' => 'charge.success',
            'data' => $this->successfulPaystackData('PSK_webhook_reference', 150000),
        ]);

        $response = $this->call('POST', route('webhooks.paystack'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_PAYSTACK_SIGNATURE' => 'invalid',
        ], $payload);

        $response->assertStatus(400);
    }

    public function test_webhook_does_not_upgrade_when_amount_does_not_match_pending_payment(): void
    {
        Mail::fake();

        Payment::create([
            'user_id' => $this->student->id,
            'paystack_reference' => 'PSK_mismatch_reference',
            'amount_kobo' => 1200000,
            'status' => 'pending',
            'plan_duration_days' => 365,
            'plan_type' => 'yearly',
        ]);

        $payload = json_encode([
            'event' => 'charge.success',
            'data' => $this->successfulPaystackData('PSK_mismatch_reference', 150000),
        ]);
        $signature = hash_hmac('sha512', $payload, 'sk_test_ready');

        $response = $this->call('POST', route('webhooks.paystack'), [], [], [], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_PAYSTACK_SIGNATURE' => $signature,
        ], $payload);

        $response->assertOk();

        $this->assertDatabaseHas('payments', [
            'paystack_reference' => 'PSK_mismatch_reference',
            'status' => 'failed',
        ]);

        $this->student->refresh();
        $this->assertNotSame('premium', $this->student->plan);
        Mail::assertNothingSent();
    }

    private function successfulPaystackData(string $reference, int $amount): array
    {
        return [
            'status' => 'success',
            'reference' => $reference,
            'amount' => $amount,
            'currency' => 'NGN',
            'customer' => [
                'email' => 'student@example.com',
            ],
            'metadata' => [
                'user_id' => $this->student->id,
            ],
        ];
    }
}
