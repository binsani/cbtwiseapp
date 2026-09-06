<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PaystackWebhookController;
use App\Livewire\Pricing;
use App\Livewire\Redeem;
use App\Livewire\UserDashboard;
use App\Livewire\Exam\Setup as ExamSetup;
use App\Livewire\Exam\Runner as ExamRunner;
use App\Livewire\Exam\Results as ExamResults;
use App\Livewire\Exam\History as ExamHistory;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\PurchaseCodes as AdminPurchaseCodes;
use App\Livewire\Admin\ReportsIndex as AdminReportsIndex;
use Illuminate\Support\Facades\Route;

// Public Front-facing Pages
Route::view('/', 'welcome');
Route::get('pricing', Pricing::class)->name('pricing');
Route::get('redeem', Redeem::class)->name('redeem');

// Public Marketing Pages
Route::get('about', [App\Http\Controllers\PageController::class, 'about'])->name('about');
Route::get('faq', [App\Http\Controllers\PageController::class, 'faq'])->name('faq');
Route::get('contact', [App\Http\Controllers\PageController::class, 'contact'])->name('contact');
Route::post('contact', [App\Http\Controllers\PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('terms', [App\Http\Controllers\PageController::class, 'terms'])->name('terms');
Route::get('privacy', [App\Http\Controllers\PageController::class, 'privacy'])->name('privacy');
Route::get('refund-policy', [App\Http\Controllers\PageController::class, 'refundPolicy'])->name('refund-policy');
Route::get('robots.txt', [App\Http\Controllers\PageController::class, 'robots']);
Route::get('sitemap.xml', [App\Http\Controllers\PageController::class, 'sitemap']);

// Exam & Subject Landing Pages
Route::get('exams', [App\Http\Controllers\ExamLandingController::class, 'index'])->name('exams.index');
Route::get('exams/{slug}', [App\Http\Controllers\ExamLandingController::class, 'show'])->name('exams.show');
Route::get('subjects', [App\Http\Controllers\SubjectLandingController::class, 'index'])->name('subjects.index');
Route::get('subjects/{slug}', [App\Http\Controllers\SubjectLandingController::class, 'show'])->name('subjects.show');

// Blog System Routes
Route::get('blog', [App\Http\Controllers\BlogController::class, 'index'])->name('blog.index');
Route::get('blog/{slug}', [App\Http\Controllers\BlogController::class, 'show'])->name('blog.show');


// Paystack Checkout and Verification
Route::post('payment/paystack/initialize', [PaymentController::class, 'initialize'])
    ->name('payment.initialize')
    ->middleware(['auth']);

Route::get('payment/paystack/callback', [PaymentController::class, 'callback'])
    ->name('payment.callback');

Route::post('webhooks/paystack', [PaystackWebhookController::class, 'handle'])
    ->name('webhooks.paystack');

// Authenticated User Panel & Practice Flow
Route::middleware(['auth', 'verified'])->group(function () {
    // User Dashboard
    Route::get('dashboard', UserDashboard::class)->name('dashboard');
    Route::get('dashboard/history', ExamHistory::class)->name('dashboard.history');

    // Exam practice flow
    Route::get('exam/setup', ExamSetup::class)->name('exam.setup');
    
    // Exam running session is protected by daily limit
    Route::get('exam/{session}/run', ExamRunner::class)
        ->name('exam.run')
        ->whereNumber('session')
        ->middleware(['daily_limit']);
        
    Route::get('exam/{session}/results', ExamResults::class)
        ->name('exam.results')
        ->whereNumber('session');

    // Dashboard Subpages
    Route::get('dashboard/performance', \App\Livewire\Dashboard\Performance::class)->name('dashboard.performance');
    Route::get('dashboard/history/{session}', \App\Livewire\Dashboard\SessionReview::class)->name('dashboard.session-review');
    Route::get('dashboard/bookmarks', \App\Livewire\Dashboard\Bookmarks::class)->name('dashboard.bookmarks');
    Route::get('dashboard/streak', \App\Livewire\Dashboard\StudyStreak::class)->name('dashboard.streak');
    Route::get('dashboard/leaderboard', \App\Livewire\Dashboard\Leaderboard::class)->name('dashboard.leaderboard');
    Route::get('dashboard/notifications', \App\Livewire\Dashboard\UserNotifications::class)->name('dashboard.notifications');
    Route::get('dashboard/referrals', \App\Livewire\Dashboard\Referrals::class)->name('dashboard.referrals');

    // Account settings subpages
    Route::get('account/profile', \App\Livewire\Account\Profile::class)->name('account.profile');
    Route::get('profile', \App\Livewire\Account\Profile::class)->name('profile');
    Route::get('account/security', \App\Livewire\Account\Security::class)->name('account.security');
    Route::get('account/subscription', \App\Livewire\Account\Subscription::class)->name('account.subscription');
    Route::get('account/purchase-codes', \App\Livewire\Account\PurchaseCodes::class)->name('account.purchase-codes');
    Route::get('account/affiliate', \App\Livewire\Account\Affiliate::class)->name('account.affiliate');
    Route::get('account/delete', \App\Livewire\Account\DeleteAccount::class)->name('account.delete');
});

// Admin Panel Routing
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('dashboard', AdminDashboard::class)->name('admin.dashboard');
    Route::get('questions', \App\Livewire\Admin\Questions::class)->name('admin.questions');
    Route::get('exams-subjects', \App\Livewire\Admin\ExamsSubjects::class)->name('admin.exams-subjects');
    Route::get('users', \App\Livewire\Admin\Users::class)->name('admin.users');
    Route::get('subscriptions', \App\Livewire\Admin\Subscriptions::class)->name('admin.subscriptions');
    Route::get('analytics', \App\Livewire\Admin\Analytics::class)->name('admin.analytics');
    Route::get('messages', \App\Livewire\Admin\Messages::class)->name('admin.messages');
    Route::get('bulk-seeder', \App\Livewire\Admin\BulkSeeder::class)->name('admin.bulk-seeder');
    Route::get('notifications', \App\Livewire\Admin\Notifications::class)->name('admin.notifications');
    Route::get('purchase-codes', AdminPurchaseCodes::class)->name('admin.purchase-codes');
    Route::get('blog', \App\Livewire\Admin\BlogAdmin::class)->name('admin.blog');
    Route::get('affiliates', \App\Livewire\Admin\Affiliates::class)->name('admin.affiliates');
});

// Moderation/Reports Routing (Admins and Moderators)
Route::middleware(['auth', 'role:admin,moderator'])->prefix('admin')->group(function () {
    Route::get('reports', AdminReportsIndex::class)->name('admin.reports');
});

// SEO Landing Pages (e.g. /utme/english-language/2024)
Route::get('{exam}/{subject}/{year}', [App\Http\Controllers\SeoPageController::class, 'show'])
    ->where('exam', '^(?!admin|dashboard|exam|account|payment|webhooks|blog|exams|subjects|pricing|redeem|about|faq|contact|terms|privacy|refund-policy|login|register).*$')
    ->where('subject', '[a-zA-Z0-9\-]+')
    ->where('year', '[0-9]{4}')
    ->name('seo.page');

require __DIR__.'/auth.php';
