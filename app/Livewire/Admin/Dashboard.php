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
    public $todayNewUsers = 0;
    public $totalRevenue = 0;
    public $todayRevenue = 0;
    public $totalQuestions = 0;
    public $flaggedQuestions = 0;

    public $subscribersCount = 0;
    public $totalTestsTaken = 0;
    public $activeSessionsCount = 0;
    public $openReportsCount = 0;
    public $unreadMessagesCount = 0;

    public $topExams = [];
    public $revenueData = [];
    public $revenueMonths = [];
    public $examDistribution = [];

    public $subjectsMap = [];

    public function mount()
    {
        // 1. DAU/WAU/MAU & User growth
        $today = now()->toDateString();
        $sevenDaysAgo = now()->subDays(7)->toDateString();
        $thirtyDaysAgo = now()->subDays(30)->toDateString();

        $this->dau = User::where('last_active_date', $today)->count();
        $this->wau = User::where('last_active_date', '>=', $sevenDaysAgo)->count();
        $this->mau = User::where('last_active_date', '>=', $thirtyDaysAgo)->count();
        $this->todayNewUsers = User::whereDate('created_at', now()->today())->count();

        // 2. Metrics
        $this->totalUsers = User::count();
        $this->subscribersCount = User::where('plan', 'premium')->count();
        
        $revenueKobo = Payment::where('status', 'success')->sum('amount_kobo');
        $this->totalRevenue = $revenueKobo / 100; // convert to Naira

        $todayRevenueKobo = Payment::where('status', 'success')->whereDate('paid_at', now()->today())->sum('amount_kobo');
        $this->todayRevenue = $todayRevenueKobo / 100;
        
        $this->totalQuestions = Question::count();
        $this->totalTestsTaken = ExamSession::where('status', 'submitted')->count();
        $this->activeSessionsCount = ExamSession::where('status', 'in_progress')->count();
        $this->flaggedQuestions = Question::where('is_flagged', true)->count();
        $this->openReportsCount = \App\Models\QuestionReport::where('status', 'open')->count();
        $this->unreadMessagesCount = \App\Models\ContactMessage::where('status', 'new')->count();

        // 3. Subjects Map
        $this->subjectsMap = \App\Models\Subject::pluck('name', 'id')->toArray();

        // 4. Top Exams
        $this->topExams = ExamSession::select('exams.name', DB::raw('count(*) as session_count'))
            ->join('exams', 'exam_sessions.exam_id', '=', 'exams.id')
            ->groupBy('exams.id', 'exams.name')
            ->orderBy('session_count', 'desc')
            ->take(5)
            ->get()
            ->toArray();

        // 5. Exam Distribution for breakdown
        $totalSessions = max(1, $this->totalTestsTaken);
        foreach ($this->topExams as $item) {
            $this->examDistribution[] = [
                'name' => $item['name'],
                'count' => $item['session_count'],
                'percent' => round(($item['session_count'] / $totalSessions) * 100, 1),
            ];
        }

        // 6. Revenue data for Chart (last 6 calendar months with defaults)
        $isSqlite = DB::getDriverName() === 'sqlite';
        $monthExpr = $isSqlite ? "strftime('%Y-%m', paid_at)" : "DATE_FORMAT(paid_at, '%Y-%m')";

        $chartBuckets = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $key = $date->format('Y-m');
            $label = $date->format('M Y');
            $chartBuckets[$key] = [
                'label' => $label,
                'total' => 0,
            ];
        }

        $sixMonthsAgo = now()->subMonths(6)->startOfMonth();
        $monthlyRevenue = Payment::where('status', 'success')
            ->where('paid_at', '>=', $sixMonthsAgo)
            ->select(
                DB::raw("{$monthExpr} as month"),
                DB::raw('sum(amount_kobo) as total')
            )
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();
            
        foreach ($monthlyRevenue as $rev) {
            if (isset($chartBuckets[$rev->month])) {
                $chartBuckets[$rev->month]['total'] = $rev->total / 100;
            }
        }

        $this->revenueMonths = array_column($chartBuckets, 'label');
        $this->revenueData = array_column($chartBuckets, 'total');
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
        $recentActivity = ExamSession::where('status', 'submitted')
            ->with(['user', 'exam'])
            ->latest('submitted_at')
            ->take(10)
            ->get();

        $questionCoverage = \App\Models\Subject::with('exam')
            ->withCount('questions')
            ->orderBy('exam_id')
            ->get();

        $jambQuestionsCount = $questionCoverage->filter(function($s) {
            $name = strtolower(($s->exam->slug ?? '') . ' ' . ($s->exam->name ?? ''));
            return str_contains($name, 'jamb') || str_contains($name, 'utme');
        })->sum('questions_count');

        $waecQuestionsCount = $questionCoverage->filter(function($s) {
            $name = strtolower(($s->exam->slug ?? '') . ' ' . ($s->exam->name ?? ''));
            return str_contains($name, 'waec') || str_contains($name, 'ssce');
        })->sum('questions_count');

        $necoQuestionsCount = $questionCoverage->filter(function($s) {
            $name = strtolower(($s->exam->slug ?? '') . ' ' . ($s->exam->name ?? ''));
            return str_contains($name, 'neco');
        })->sum('questions_count');

        return view('livewire.admin.dashboard', [
            'recentActivity' => $recentActivity,
            'questionCoverage' => $questionCoverage,
            'jambQuestionsCount' => $jambQuestionsCount,
            'waecQuestionsCount' => $waecQuestionsCount,
            'necoQuestionsCount' => $necoQuestionsCount,
        ])->layout('layouts.app');
    }
}
