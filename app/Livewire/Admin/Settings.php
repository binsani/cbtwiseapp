<?php

namespace App\Livewire\Admin;

use App\Models\SystemSetting;
use App\Services\AdminLogger;
use Livewire\Component;

class Settings extends Component
{
    // General Settings
    public $app_name = 'CBTWise';
    public $support_email = 'support@cbtwise.com.ng';
    public $contact_phone = '+234 800 000 0000';
    public $default_timezone = 'Africa/Lagos';
    public $default_currency = 'NGN';
    public $maintenance_mode = false;

    // Exam Settings
    public $free_daily_limit = 20;
    public $default_duration_minutes = 120;
    public $default_passing_score = 50;
    public $negative_marking = false;
    public $max_practice_questions = 40;

    // Question Bank Settings
    public $dedupe_char_length = 60;
    public $default_difficulty = 'easy';
    public $require_explanation = false;
    public $auto_flag_reports_threshold = 3;

    // Notification Settings
    public $email_notifications = true;
    public $browser_sound_alerts = true;
    public $report_alerts = true;
    public $payment_alerts = true;

    // Payment Settings
    public $paystack_public_key = '';
    public $paystack_secret_key = '';
    public $minimum_payment_amount = 500;

    public $activeTab = 'general'; // general, exams, questions, notifications, payment

    public function mount()
    {
        $this->app_name = SystemSetting::get('app_name', config('app.name', 'CBTWise'));
        $this->support_email = SystemSetting::get('support_email', 'support@cbtwise.com.ng');
        $this->contact_phone = SystemSetting::get('contact_phone', '+234 800 000 0000');
        $this->default_timezone = SystemSetting::get('default_timezone', config('app.timezone', 'Africa/Lagos'));
        $this->default_currency = SystemSetting::get('default_currency', 'NGN');
        $this->maintenance_mode = (bool) SystemSetting::get('maintenance_mode', false);

        $this->free_daily_limit = (int) SystemSetting::get('free_daily_limit', config('cbtwise.free_daily_limit', 20));
        $this->default_duration_minutes = (int) SystemSetting::get('default_duration_minutes', 120);
        $this->default_passing_score = (int) SystemSetting::get('default_passing_score', 50);
        $this->negative_marking = (bool) SystemSetting::get('negative_marking', false);
        $this->max_practice_questions = (int) SystemSetting::get('max_practice_questions', 40);

        $this->dedupe_char_length = (int) SystemSetting::get('dedupe_char_length', config('cbtwise.dedupe_char_length', 60));
        $this->default_difficulty = SystemSetting::get('default_difficulty', 'easy');
        $this->require_explanation = (bool) SystemSetting::get('require_explanation', false);
        $this->auto_flag_reports_threshold = (int) SystemSetting::get('auto_flag_reports_threshold', 3);

        $this->email_notifications = (bool) SystemSetting::get('email_notifications', true);
        $this->browser_sound_alerts = (bool) SystemSetting::get('browser_sound_alerts', true);
        $this->report_alerts = (bool) SystemSetting::get('report_alerts', true);
        $this->payment_alerts = (bool) SystemSetting::get('payment_alerts', true);

        $this->paystack_public_key = SystemSetting::get('paystack_public_key', config('cbtwise.paystack.public_key', ''));
        $this->paystack_secret_key = SystemSetting::get('paystack_secret_key', '');
        $this->minimum_payment_amount = (int) SystemSetting::get('minimum_payment_amount', 500);
    }

    public function saveSettings()
    {
        $this->validate([
            'app_name' => 'required|string|max:100',
            'support_email' => 'required|email|max:150',
            'free_daily_limit' => 'required|integer|min:1|max:500',
            'default_passing_score' => 'required|integer|min:10|max:100',
            'dedupe_char_length' => 'required|integer|min:20|max:200',
        ]);

        // General
        SystemSetting::set('app_name', $this->app_name, 'general');
        SystemSetting::set('support_email', $this->support_email, 'general');
        SystemSetting::set('contact_phone', $this->contact_phone, 'general');
        SystemSetting::set('default_timezone', $this->default_timezone, 'general');
        SystemSetting::set('default_currency', $this->default_currency, 'general');
        SystemSetting::set('maintenance_mode', $this->maintenance_mode ? '1' : '0', 'general');

        // Exams
        SystemSetting::set('free_daily_limit', (string) $this->free_daily_limit, 'exam');
        SystemSetting::set('default_duration_minutes', (string) $this->default_duration_minutes, 'exam');
        SystemSetting::set('default_passing_score', (string) $this->default_passing_score, 'exam');
        SystemSetting::set('negative_marking', $this->negative_marking ? '1' : '0', 'exam');
        SystemSetting::set('max_practice_questions', (string) $this->max_practice_questions, 'exam');

        // Questions
        SystemSetting::set('dedupe_char_length', (string) $this->dedupe_char_length, 'question');
        SystemSetting::set('default_difficulty', $this->default_difficulty, 'question');
        SystemSetting::set('require_explanation', $this->require_explanation ? '1' : '0', 'question');
        SystemSetting::set('auto_flag_reports_threshold', (string) $this->auto_flag_reports_threshold, 'question');

        // Notifications
        SystemSetting::set('email_notifications', $this->email_notifications ? '1' : '0', 'notification');
        SystemSetting::set('browser_sound_alerts', $this->browser_sound_alerts ? '1' : '0', 'notification');
        SystemSetting::set('report_alerts', $this->report_alerts ? '1' : '0', 'notification');
        SystemSetting::set('payment_alerts', $this->payment_alerts ? '1' : '0', 'notification');

        // Payments (Encrypted)
        SystemSetting::set('paystack_public_key', $this->paystack_public_key, 'payment');
        if (!empty($this->paystack_secret_key)) {
            SystemSetting::set('paystack_secret_key', $this->paystack_secret_key, 'payment', true);
        }
        SystemSetting::set('minimum_payment_amount', (string) $this->minimum_payment_amount, 'payment');

        AdminLogger::log('settings.updated', SystemSetting::class, [
            'tab' => $this->activeTab,
            'app_name' => $this->app_name,
        ]);

        session()->flash('message', 'System settings saved successfully!');
    }

    public function render()
    {
        return view('livewire.admin.settings')
            ->layout('layouts.app');
    }
}
