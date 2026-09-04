<?php

namespace App\Livewire;

use App\Models\PurchaseCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Redeem extends Component
{
    // Common fields
    public $code;
    public $recaptchaToken;

    // Guest Registration fields
    public $name;
    public $email;
    public $phone;
    public $state;
    public $exam_year;

    protected $rules = [
        'code' => 'required|string',
    ];

    public function mount()
    {
        $this->exam_year = date('Y') + 1;
    }

    public function verifyRecaptcha(): bool
    {
        $secret = config('services.recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY');

        if (app()->environment('testing') || !$secret) {
            return true;
        }

        if (!$this->recaptchaToken) {
            $this->addError('code', 'reCAPTCHA token is missing. Please refresh and try again.');
            return false;
        }

        try {
            $response = Http::asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $secret,
                'response' => $this->recaptchaToken,
            ]);

            if ($response->successful()) {
                $result = $response->json();
                if (($result['success'] ?? false) && ($result['score'] ?? 0.5) >= 0.5) {
                    return true;
                }
            }
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
        }

        $this->addError('code', 'Security verification failed. Please try again.');
        return false;
    }

    public function redeem()
    {
        $this->validate();

        // 1. Verify reCAPTCHA
        if (!$this->verifyRecaptcha()) {
            return;
        }

        // 2. Find purchase code
        $purchaseCode = PurchaseCode::where('code', strtoupper(trim($this->code)))->first();

        if (!$purchaseCode) {
            $this->addError('code', 'This purchase code is invalid.');
            return;
        }

        if ($purchaseCode->isUsed()) {
            $this->addError('code', 'This purchase code has already been used.');
            return;
        }

        if (Auth::check()) {
            // User is logged in, apply code directly
            $this->applyCodeToUser(Auth::user(), $purchaseCode);
        } else {
            // User is guest, validate guest signup fields
            $this->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'phone' => 'nullable|string|max:20',
                'state' => 'required|string|max:50',
                'exam_year' => 'required|integer|min:2000|max:' . (date('Y') + 5),
            ]);

            // Create user with the code itself as the password reference
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'state' => $this->state,
                'school' => null,
                'exam_year' => $this->exam_year,
                'password' => Hash::make($this->code),
                'email_verified_at' => now(), // Auto-verify guest redeeming code
            ]);

            $user->assignRole('user');

            // Apply purchase code
            $this->applyCodeToUser($user, $purchaseCode);

            // Log user in
            Auth::login($user);
        }

        session()->flash('success', 'Your code has been successfully redeemed! Welcome to Premium.');
        return redirect()->route('dashboard');
    }

    protected function applyCodeToUser(User $user, PurchaseCode $purchaseCode)
    {
        // Mark code as used
        $purchaseCode->update([
            'used_by_user_id' => $user->id,
            'used_at' => now(),
        ]);

        // Extend premium access
        $currentExpiry = $user->premium_expires_at;
        $baseDate = ($currentExpiry && $currentExpiry->isFuture()) ? $currentExpiry : now();

        $user->update([
            'plan' => 'premium',
            'premium_expires_at' => $baseDate->addDays($purchaseCode->plan_duration_days),
        ]);
    }

    public function render()
    {
        return view('livewire.redeem', [
            'states' => config('cbtwise.states', []),
        ])->layout('layouts.app');
    }
}
