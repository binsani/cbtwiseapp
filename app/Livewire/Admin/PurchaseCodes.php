<?php

namespace App\Livewire\Admin;

use App\Models\PurchaseCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PurchaseCodes extends Component
{
    use WithPagination;

    // Batch Generation Fields
    public $studentName = '';
    public $quantity = 1;
    public $durationDays = 30;
    public $notes = '';

    // Filter fields
    public $filterStatus = 'all'; // all, active (unused), used, cancelled
    public $search = '';

    // Modal forms state
    public $isModalOpen = false;

    protected $queryString = [
        'filterStatus' => ['except' => 'all'],
        'search' => ['except' => ''],
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFilterStatus()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    public function resetForm()
    {
        $this->studentName = '';
        $this->quantity = 1;
        $this->durationDays = 30;
        $this->notes = '';
    }

    public function generateBatch()
    {
        $this->validate([
            'studentName' => 'nullable|string|max:100',
            'quantity' => 'required|integer|min:1|max:100',
            'durationDays' => 'required|integer|min:1|max:3650',
            'notes' => 'nullable|string|max:255',
        ]);

        $adminId = Auth::id();

        for ($i = 0; $i < $this->quantity; $i++) {
            PurchaseCode::generate($adminId, $this->durationDays, $this->studentName ?: null, $this->notes ?: null);
        }

        // Record audit trail event
        \App\Models\AdminActivityLog::record(
            $adminId,
            'purchase_code.batch_generated',
            null,
            null,
            ['duration_days' => $this->durationDays, 'quantity' => $this->quantity, 'student_name' => $this->studentName]
        );

        session()->flash('message', "Successfully generated {$this->quantity} purchase codes of {$this->durationDays} days duration.");
        $this->closeModal();
    }
    public function downloadCsv(): StreamedResponse
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=purchase_codes_' . now()->toDateString() . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Code', 'Duration Days', 'Status', 'Used By Student', 'Used By Email', 'Created At']);

            PurchaseCode::with('usedBy')
                ->chunk(100, function($codes) use ($file) {
                    foreach ($codes as $c) {
                        fputcsv($file, [
                            $c->id,
                            $c->code,
                            $c->plan_duration_days,
                            $c->isUsed() ? 'used' : 'active',
                            $c->usedBy->name ?? 'N/A',
                            $c->usedBy->email ?? 'N/A',
                            $c->created_at->toDateTimeString(),
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        // 1. Core analytics counts
        $totalCodes = PurchaseCode::count();
        $activeCodes = PurchaseCode::whereNull('used_by_user_id')->count();
        $usedCodes = PurchaseCode::whereNotNull('used_by_user_id')->count();
        $cancelledCodes = 0; // standard placeholder count matching layout

        // 2. Query execution
        $query = PurchaseCode::query()
            ->with(['usedBy', 'createdBy']);

        if ($this->filterStatus === 'active') {
            $query->whereNull('used_by_user_id');
        } elseif ($this->filterStatus === 'used') {
            $query->whereNotNull('used_by_user_id');
        }

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('code', 'like', '%' . strtoupper($this->search) . '%')
                  ->orWhereHas('usedBy', function($u) {
                      $u->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                  });
            });
        }

        $codes = $query->latest()->paginate(15);

        return view('livewire.admin.purchase-codes', [
            'codes' => $codes,
            'totalCodes' => $totalCodes,
            'activeCodes' => $activeCodes,
            'usedCodes' => $usedCodes,
            'cancelledCodes' => $cancelledCodes,
        ])->layout('layouts.app');
    }
}
