<?php

namespace App\Livewire\Admin;

use App\Models\Affiliate;
use App\Models\AffiliatePayout;
use Livewire\Component;
use Livewire\WithPagination;

class Affiliates extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';
    
    // Manual payout recording form
    public $showPayoutModal = false;
    public $selectedAffiliateId;
    public $payoutAmount;
    public $paystackReference;
    
    protected $updatesQueryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function changeStatus($affiliateId, $newStatus)
    {
        if (!in_array($newStatus, ['pending', 'active', 'suspended'])) {
            return;
        }

        $affiliate = Affiliate::findOrFail($affiliateId);
        $affiliate->update([
            'status' => $newStatus,
            'approved_at' => $newStatus === 'active' ? now() : $affiliate->approved_at,
        ]);

        session()->flash('success', "Affiliate status updated to {$newStatus}.");
    }

    public function openPayoutModal($affiliateId)
    {
        $affiliate = Affiliate::findOrFail($affiliateId);
        $this->selectedAffiliateId = $affiliateId;
        $this->payoutAmount = $affiliate->balance_ngn;
        $this->paystackReference = 'MAN-' . strtoupper(bin2hex(random_bytes(6)));
        $this->showPayoutModal = true;
    }

    public function recordPayout()
    {
        $this->validate([
            'payoutAmount' => 'required|numeric|min:1',
            'paystackReference' => 'required|string|unique:affiliate_payouts,paystack_reference',
        ]);

        $affiliate = Affiliate::findOrFail($this->selectedAffiliateId);

        if ($this->payoutAmount > $affiliate->balance_ngn) {
            $this->addError('payoutAmount', 'Payout amount cannot exceed affiliate balance.');
            return;
        }

        // Create the payout as successful since it is recorded manually
        AffiliatePayout::create([
            'affiliate_id' => $affiliate->id,
            'amount_ngn' => $this->payoutAmount,
            'paystack_reference' => $this->paystackReference,
            'status' => 'success',
            'paid_at' => now(),
        ]);

        // Deduct balance and update total earned
        $affiliate->decrement('balance_ngn', $this->payoutAmount);
        $affiliate->increment('total_earned_ngn', $this->payoutAmount);

        $this->showPayoutModal = false;
        session()->flash('success', 'Manual payout recorded successfully.');
    }

    public function render()
    {
        $query = Affiliate::query()->with('user');

        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $affiliates = $query->latest()->paginate(15);

        // Fetch pending payout requests for admin to review
        $pendingPayouts = AffiliatePayout::where('status', 'pending')
            ->with('affiliate.user')
            ->latest()
            ->get();

        return view('livewire.admin.affiliates', [
            'affiliates' => $affiliates,
            'pendingPayouts' => $pendingPayouts,
        ])->layout('layouts.app');
    }
}
