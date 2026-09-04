<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Payment;
use App\Models\PurchaseCode;
use Livewire\Component;
use Livewire\WithPagination;

class Subscriptions extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all'; // all, active, expired, cancelled

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedStatusFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        // 1. Get totals for badges
        $totalCount = User::whereNotNull('plan')->count();
        
        $activeCount = User::where('plan', 'premium')
            ->where(function($q) {
                $q->whereNull('premium_expires_at')
                  ->orWhere('premium_expires_at', '>', now());
            })->count();

        $expiredCount = User::where('plan', 'premium')
            ->whereNotNull('premium_expires_at')
            ->where('premium_expires_at', '<=', now())
            ->count();

        // Standardise status tracking.
        // A subscriber can either be free (cancelled/expired if they had premium before, but let's count free plan users as cancelled/inactive)
        $cancelledCount = User::where('plan', 'free')->count();

        // 2. Fetch the records
        $query = User::query()
            ->whereNotNull('plan');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhere('plan', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter === 'active') {
            $query->where('plan', 'premium')
                  ->where(function($q) {
                      $q->whereNull('premium_expires_at')
                        ->orWhere('premium_expires_at', '>', now());
                  });
        } elseif ($this->statusFilter === 'expired') {
            $query->where('plan', 'premium')
                  ->whereNotNull('premium_expires_at')
                  ->where('premium_expires_at', '<=', now());
        } elseif ($this->statusFilter === 'cancelled') {
            $query->where('plan', 'free');
        }

        $users = $query->latest('created_at')->paginate(15);

        // Pre-populate premium starts, ends, and references mapping
        $records = [];
        foreach ($users as $user) {
            // Find reference code/payment
            $ref = '—';
            $starts = $user->created_at;
            $ends = $user->premium_expires_at;

            // Check if redeemed via offline code
            $usedCode = PurchaseCode::where('used_by_user_id', $user->id)->latest('used_at')->first();
            if ($usedCode) {
                $ref = 'CODE: ' . $usedCode->code;
                $starts = $usedCode->used_at;
            } else {
                // Check if paid via Paystack
                $payment = Payment::where('user_id', $user->id)->where('status', 'success')->latest('paid_at')->first();
                if ($payment) {
                    $ref = $payment->paystack_reference;
                    $starts = $payment->paid_at;
                }
            }

            // Calculate active state
            $status = 'cancelled';
            if ($user->plan === 'premium') {
                if (!$user->premium_expires_at || $user->premium_expires_at->isFuture()) {
                    $status = 'active';
                } else {
                    $status = 'expired';
                }
            }

            // Mapped Plan Labels
            $duration = '30-Day';
            if ($ends && $starts) {
                $days = $starts->diffInDays($ends);
                if ($days > 300) {
                    $duration = '365-Day';
                } elseif ($days > 80) {
                    $duration = '90-Day';
                }
            }
            $planLabel = $user->plan === 'premium' ? "{$duration} Premium" : 'Free Tier';

            $records[] = [
                'user_id' => $user->id,
                'plan' => $planLabel,
                'status' => $status,
                'starts' => $starts ? $starts->format('n/j/Y') : '—',
                'ends' => $ends ? $ends->format('n/j/Y') : '—',
                'ref' => $ref,
            ];
        }

        return view('livewire.admin.subscriptions', [
            'records' => $records,
            'paginator' => $users,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'expiredCount' => $expiredCount,
            'cancelledCount' => $cancelledCount,
        ])->layout('layouts.app');
    }
}
