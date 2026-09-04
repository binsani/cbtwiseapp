<?php

namespace App\Livewire\Account;

use App\Models\Affiliate as AffiliateModel;
use App\Models\AffiliatePayout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Affiliate extends Component
{
    public $affiliate;
    public $isApplied = false;

    // Form fields for payout details
    public $bank_code;
    public $account_number;
    public $account_name;
    public $payout_amount;
    
    public $banks = [];
    public $isResolving = false;
    public $resolvedName = '';

    protected $rules = [
        'bank_code' => 'required|string',
        'account_number' => 'required|string|digits:10',
        'account_name' => 'required|string|min:3',
        'payout_amount' => 'required|numeric|min:5000',
    ];

    public function mount()
    {
        $user = Auth::user();
        $this->affiliate = $user->affiliate;
        
        if ($this->affiliate) {
            $this->isApplied = true;
            $this->bank_code = $this->affiliate->bank_code;
            $this->account_number = $this->affiliate->account_number;
            $this->account_name = $this->affiliate->account_name;
            $this->payout_amount = $this->affiliate->balance_ngn;
        }

        $this->loadBanks();
    }

    public function apply()
    {
        $user = Auth::user();
        if ($user->affiliate) {
            session()->flash('error', 'You are already registered.');
            return;
        }

        $this->affiliate = AffiliateModel::create([
            'user_id' => $user->id,
            'status' => 'active', // Auto-approve to streamline
            'balance_ngn' => 0,
            'total_earned_ngn' => 0,
            'approved_at' => now(),
        ]);

        $this->isApplied = true;
        $this->payout_amount = 0;
        session()->flash('success', 'Congratulations! You are now an active CBTWise Affiliate.');
    }

    public function loadBanks()
    {
        $this->banks = Cache::remember('nigerian_banks', 24 * 3600, function () {
            $secretKey = config('cbtwise.paystack.secret_key');
            if (!$secretKey) {
                return [];
            }

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $secretKey,
                ])->get('https://api.paystack.co/bank', [
                    'country' => 'nigeria',
                ]);

                if ($response->successful()) {
                    return $response->json()['data'] ?? [];
                }
            } catch (\Exception $e) {
                Log::error('Failed to fetch Paystack banks: ' . $e->getMessage());
            }

            return [];
        });
    }

    public function updatedAccountNumber()
    {
        $this->resolveAccount();
    }

    public function updatedBankCode()
    {
        $this->resolveAccount();
    }

    public function resolveAccount()
    {
        if (strlen($this->account_number) === 10 && !empty($this->bank_code)) {
            $this->isResolving = true;
            $this->resolvedName = '';
            
            $secretKey = config('cbtwise.paystack.secret_key');
            if (!$secretKey) {
                $this->isResolving = false;
                return;
            }

            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $secretKey,
                ])->get('https://api.paystack.co/bank/resolve', [
                    'account_number' => $this->account_number,
                    'bank_code' => $this->bank_code,
                ]);

                if ($response->successful()) {
                    $data = $response->json()['data'] ?? [];
                    $this->resolvedName = $data['account_name'] ?? '';
                    $this->account_name = $this->resolvedName;
                } else {
                    $this->addError('account_number', 'Could not resolve account details. Please check numbers.');
                }
            } catch (\Exception $e) {
                Log::error('Paystack account resolution error: ' . $e->getMessage());
            }

            $this->isResolving = false;
        }
    }

    public function requestPayout()
    {
        $this->validate();

        if (!$this->affiliate || !$this->affiliate->isActive()) {
            session()->flash('error', 'Your affiliate account is not active.');
            return;
        }

        if ($this->affiliate->balance_ngn < 5000) {
            session()->flash('error', 'Minimum payout amount is ₦5,000.');
            return;
        }

        if ($this->payout_amount > $this->affiliate->balance_ngn) {
            $this->addError('payout_amount', 'Amount exceeds your current balance.');
            return;
        }

        // Save bank details to profile
        $this->affiliate->update([
            'bank_code' => $this->bank_code,
            'account_number' => $this->account_number,
            'account_name' => $this->account_name,
        ]);

        // Create payout request
        AffiliatePayout::create([
            'affiliate_id' => $this->affiliate->id,
            'amount_ngn' => $this->payout_amount,
            'status' => 'pending',
        ]);

        // Deduct from balance
        $this->affiliate->decrement('balance_ngn', $this->payout_amount);

        // Refresh model
        $this->affiliate->refresh();
        $this->payout_amount = $this->affiliate->balance_ngn;

        session()->flash('success', 'Payout request of ₦' . number_format($this->payout_amount, 2) . ' submitted successfully.');
    }

    public function render()
    {
        $clicksCount = 0;
        $conversions = collect();
        $payouts = collect();
        
        if ($this->affiliate) {
            $clicksCount = $this->affiliate->clicks()->count();
            $conversions = $this->affiliate->conversions()->with('referredUser')->latest()->get();
            $payouts = $this->affiliate->payouts()->latest()->get();
        }

        return view('livewire.account.affiliate', [
            'clicksCount' => $clicksCount,
            'conversions' => $conversions,
            'payouts' => $payouts,
        ])->layout('layouts.app');
    }
}
