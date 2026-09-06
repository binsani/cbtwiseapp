<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\Payment;
use App\Models\Question;
use App\Models\ExamSession;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Dashboard extends Component
{
    public $dau = 0;
    public $wau = 0;
    public $mau = 0;
    
    public $totalUsers = 0;
    public $totalRevenue = 0;
    public $totalQuestions = 0;
    public $flaggedQuestions = 0;

    public $subscribersCount = 0;
    public $totalTestsTaken = 0;

    public $topExams = [];
    public $revenueData = [];

    public $recentActivity = [];
    public $questionCoverage = [];
    public $subjectsMap = [];

    public function mount()
    {
        // 1. DAU/WAU/MAU
        $today = now()->toDateString();
        $sevenDaysAgo = now()->subDays(7)->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $this->dau = User::where('last_active_date', $today)->count();
        $this->wau = User::where('last_active_date', '>=', $sevenDaysAgo)->count();
        $this->mau = User::where('last_active_date', '>=', $thirtyDaysAgo)->count();

        // 2. Metrics
        $this->totalUsers = User::count();
        $this->subscribersCount = User::where('plan', 'premium')->count();
        
        $revenueKobo = Payment::where('status', 'success')->sum('amount_kobo');
        $this->totalRevenue = $revenueKobo / 100; // convert to Naira
        
        $this->totalQuestions = Question::count();
        $this->totalTestsTaken = ExamSession::where('status', 'submitted')->count();
        $this->flaggedQuestions = Question::where('is_flagged', true)->count();

        // 3. Recent Activity & Question Coverage
        $this->recentActivity = ExamSession::where('status', 'submitted')
            ->with(['user', 'exam'])
            ->latest('submitted_at')
            ->take(8)
            ->get();

        $this->subjectsMap = \App\Models\Subject::pluck('name', 'id')->toArray();

        $this->questionCoverage = \App\Models\Subject::with('exam')
            ->withCount('questions')
            ->orderBy('exam_id')
            ->get();

        // 4. Top Exams
        $this->topExams = ExamSession::select('exams.name', DB::raw('count(*) as session_count'))
            ->join('exams', 'exam_sessions.exam_id', '=', 'exams.id')
            ->groupBy('exams.id', 'exams.name')
            ->orderBy('session_count', 'desc')
            ->take(5)
            ->get()
            ->toArray();

        // 5. Revenue data for Chart (last 6 months)
        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();
        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "strftime('%Y-%m', paid_at)" : "DATE_FORMAT(paid_at, '%Y-%m')";
        
        $monthlyRevenue = Payment::where('status', 'success')
            ->where('paid_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw("{$monthExpr} as month"),
                DB::raw('sum(amount_kobo) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
            
        // Convert to array
        foreach ($monthlyRevenue as $rev) {
            $this->revenueData[$rev->month] = $rev->total / 100;
        }
    }

    public function clearSystemCache()
    {
        \Illuminate\Support\Facades\Artisan::call('view:clear');
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
        \Illuminate\Support\Facades\Artisan::call('route:clear');

        session()->flash('message', 'System views, application cache, and route cache cleared successfully!');
    }

    /**
     * Export all users to CSV.
     */
    public function exportUsers(): StreamedResponse
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=users_export_' . now()->toDateString() . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Name', 'Email', 'Plan', 'Premium Expires At', 'Streak Days', 'Created At']);

            User::chunk(100, function($users) use ($file) {
                foreach ($users as $user) {
                    fputcsv($file, [
                        $user->id,
                        $user->name,
                        $user->email,
                        $user->plan,
                        $user->premium_expires_at ? $user->premium_expires_at->toDateTimeString() : 'N/A',
                        $user->study_streak_days,
                        $user->created_at->toDateTimeString(),
                    ]);
                }
            });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export all successful payments to CSV.
     */
    public function exportPayments(): StreamedResponse
    {
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename=payments_export_' . now()->toDateString() . '.csv',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'User ID', 'User Name', 'User Email', 'Reference', 'Amount (NGN)', 'Plan Type', 'Paid At']);

            Payment::where('status', 'success')
                ->with('user')
                ->chunk(100, function($payments) use ($file) {
                    foreach ($payments as $payment) {
                        fputcsv($file, [
                            $payment->id,
                            $payment->user_id,
                            $payment->user->name ?? 'Deleted User',
                            $payment->user->email ?? 'N/A',
                            $payment->paystack_reference,
                            $payment->amount_kobo / 100,
                            $payment->plan_type,
                            $payment->paid_at ? $payment->paid_at->toDateTimeString() : 'N/A',
                        ]);
                    }
                });

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function render()
    {
        return view('livewire.admin.dashboard')
            ->layout('layouts.app');
    }
}
