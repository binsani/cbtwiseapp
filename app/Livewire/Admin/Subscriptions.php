<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Payment;
use App\Models\PurchaseCode;
use App\Services\AdminLogger;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function manualActivate($userId, $days = 30)
    {
        $user = User::findOrFail($userId);
        $baseDate = ($user->premium_expires_at && $user->premium_expires_at->isFuture()) 
            ? $user->premium_expires_at 
            : now();

        $user->update([
            'plan' => 'premium',
            'premium_expires_at' => $baseDate->addDays((int)$days),
        ]);

        AdminLogger::log('subscription.manual_activated', $user, ['days' => $days]);
        session()->flash('message', "Subscription activated for {$user->name} for {$days} days.");
    }

    public function extendSubscription($userId, $days = 30)
    {
        $this->manualActivate($userId, $days);
    }

    public function cancelSubscription($userId)
    {
        $user = User::findOrFail($userId);
        $user->update([
            'plan' => 'free',
            'premium_expires_at' => null,
        ]);

        AdminLogger::log('subscription.cancelled', $user);
        session()->flash('message', "Subscription cancelled for {$user->name}. Reverted to free tier.");
    }

    public function exportCsv(): StreamedResponse
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=subscriptions_export_' . now()->toDateString() . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['User ID', 'Name', 'Email', 'Plan', 'Status', 'Expires At', 'Reference']);

            User::whereNotNull('plan')->chunk(100, function($users) use ($file) {
                foreach ($users as $u) {
                    $status = ($u->plan === 'premium' && (!$u->premium_expires_at || $u->premium_expires_at->isFuture())) 
                        ? 'active' 
                        : ($u->plan === 'premium' ? 'expired' : 'free');

                    $payment = Payment::where('user_id', $u->id)->where('status', 'success')->latest('paid_at')->first();
                    $ref = $payment ? $payment->paystack_reference : 'N/A';

                    fputcsv($file, [
                        $u->id,
                        $u->name,
                        $u->email,
                        $u->plan,
                        $status,
                        $u->premium_expires_at ? $u->premium_expires_at->toDateTimeString() : 'N/A',
                        $ref,
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
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

        $cancelledCount = User::where('plan', 'free')->count();

        // 2. Fetch the records
        $query = User::query()->whereNotNull('plan');

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%');
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
            $ref = '—';
            $starts = $user->created_at;
            $ends = $user->premium_expires_at;

            $usedCode = PurchaseCode::where('used_by_user_id', $user->id)->latest('used_at')->first();
            if ($usedCode) {
                $ref = 'CODE: ' . $usedCode->code;
                $starts = $usedCode->used_at;
            } else {
                $payment = Payment::where('user_id', $user->id)->where('status', 'success')->latest('paid_at')->first();
                if ($payment) {
                    $ref = $payment->paystack_reference;
                    $starts = $payment->paid_at;
                }
            }

            $computedStatus = 'active';
            if ($user->plan === 'free') {
                $computedStatus = 'free';
            } elseif ($ends && $ends->isPast()) {
                $computedStatus = 'expired';
            }

            $records[] = [
                'user' => $user,
                'starts' => $starts,
                'ends' => $ends,
                'ref' => $ref,
                'status' => $computedStatus,
            ];
        }

        return view('livewire.admin.subscriptions', [
            'records' => $records,
            'users' => $users,
            'totalCount' => $totalCount,
            'activeCount' => $activeCount,
            'expiredCount' => $expiredCount,
            'cancelledCount' => $cancelledCount,
        ])->layout('layouts.app');
    }
}
