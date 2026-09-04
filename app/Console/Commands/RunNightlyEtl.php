<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RunNightlyEtl extends Command
{
    protected $signature   = 'cbtwise:nightly-etl {--date= : Process a specific date (Y-m-d), defaults to yesterday}';
    protected $description = 'Incremental ETL from transactional tables into analytics schema.';

    public function handle(): int
    {
        $date = $this->option('date') ?? now()->subDay()->toDateString();
        $this->info("🔄 Running ETL for date: {$date}");

        $this->etlAttempts($date);
        $this->etlSessions($date);
        $this->etlRevenue($date);
        $this->etlDimUsers();

        $this->info('✅ Nightly ETL complete.');
        return self::SUCCESS;
    }

    private function etlAttempts(string $date): void
    {
        $this->line('  → Syncing analytics_fact_attempts...');

        // Idempotent: delete then re-insert
        DB::table('analytics_fact_attempts')->whereDate('created_date', $date)->delete();

        $driver = DB::getDriverName();

        if ($driver === 'sqlite') {
            // SQLite-compatible version
            $rows = DB::table('exam_answers as ea')
                ->join('exam_sessions as es', 'ea.session_id', '=', 'es.id')
                ->join('questions as q', 'ea.question_id', '=', 'q.id')
                ->whereDate('ea.created_at', $date)
                ->select([
                    'ea.session_id', 'es.user_id', 'ea.question_id',
                    'q.subject_id', 'q.exam_id',
                    DB::raw("(CASE WHEN ea.selected_option = ea.correct_option THEN 1 ELSE 0 END) as is_correct"),
                    DB::raw("date(ea.created_at) as created_date"),
                ])
                ->get();

            foreach ($rows->chunk(500) as $chunk) {
                DB::table('analytics_fact_attempts')->insert(
                    $chunk->map(fn ($r) => array_merge((array) $r, [
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]))->toArray()
                );
            }
        } else {
            DB::statement("
                INSERT INTO analytics_fact_attempts
                    (session_id, user_id, question_id, subject_id, exam_id, is_correct, created_date, created_at, updated_at)
                SELECT
                    ea.session_id, es.user_id, ea.question_id, q.subject_id, q.exam_id,
                    (ea.selected_option = ea.correct_option) as is_correct,
                    DATE(ea.created_at) as created_date,
                    NOW(), NOW()
                FROM exam_answers ea
                JOIN exam_sessions es ON ea.session_id = es.id
                JOIN questions q ON ea.question_id = q.id
                WHERE DATE(ea.created_at) = ?
            ", [$date]);
        }

        $count = DB::table('analytics_fact_attempts')->whereDate('created_date', $date)->count();
        $this->line("     Loaded {$count} attempt rows.");
    }

    private function etlSessions(string $date): void
    {
        $this->line('  → Syncing analytics_fact_sessions...');
        DB::table('analytics_fact_sessions')->whereDate('created_date', $date)->delete();

        $rows = DB::table('exam_sessions as es')
            ->whereDate('es.created_at', $date)
            ->whereNotNull('es.completed_at')
            ->select([
                'es.id as session_id', 'es.user_id', 'es.exam_id',
                DB::raw('COALESCE(es.score, 0) as score'),
                DB::raw('date(es.created_at) as created_date'),
            ])
            ->get();

        foreach ($rows->chunk(500) as $chunk) {
            DB::table('analytics_fact_sessions')->insert(
                $chunk->map(fn ($r) => array_merge((array) $r, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]))->toArray()
            );
        }

        $this->line("     Loaded {$rows->count()} session rows.");
    }

    private function etlRevenue(string $date): void
    {
        $this->line('  → Syncing analytics_fact_revenue...');
        DB::table('analytics_fact_revenue')->whereDate('created_date', $date)->delete();

        $rows = DB::table('payments')
            ->whereDate('created_at', $date)
            ->where('status', 'success')
            ->select([
                'id as payment_id', 'user_id', 'amount as amount_ngn',
                DB::raw("'premium' as plan"),
                DB::raw("'paystack' as source"),
                DB::raw('date(created_at) as created_date'),
            ])
            ->get();

        foreach ($rows->chunk(500) as $chunk) {
            DB::table('analytics_fact_revenue')->insert(
                $chunk->map(fn ($r) => array_merge((array) $r, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]))->toArray()
            );
        }
    }

    private function etlDimUsers(): void
    {
        $this->line('  → Refreshing analytics_dim_user snapshot...');
        $today = now()->toDateString();

        $users = DB::table('users')->select('id', 'plan', 'state', 'exam_year')->get();

        foreach ($users->chunk(200) as $chunk) {
            foreach ($chunk as $user) {
                $firstExam  = DB::table('exam_sessions')->where('user_id', $user->id)->min('created_at');
                $firstPaid  = DB::table('payments')->where('user_id', $user->id)->where('status', 'success')->min('created_at');

                DB::table('analytics_dim_user')->updateOrInsert(
                    ['user_id' => $user->id],
                    [
                        'plan'          => $user->plan,
                        'state'         => $user->state,
                        'exam_year'     => $user->exam_year,
                        'first_exam_at' => $firstExam,
                        'first_paid_at' => $firstPaid,
                        'snapshot_date' => $today,
                        'updated_at'    => now(),
                        'created_at'    => now(),
                    ]
                );
            }
        }

        $this->line("     Dim user snapshot refreshed.");
    }
}
