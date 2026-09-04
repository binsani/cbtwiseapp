<?php

namespace App\Http\Controllers;

use App\Models\ExamSession;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('public_stats', 3600, function () {
            return [
                'total_users'        => User::count(),
                'exams_taken'        => ExamSession::whereNotNull('completed_at')->count(),
                'questions_answered' => DB::table('exam_answers')->count(),
                'subjects_available' => DB::table('subjects')->count(),
                'questions_bank'     => DB::table('questions')->where('is_flagged', false)->count(),
            ];
        });

        return view('pages.stats', compact('stats'));
    }
}
