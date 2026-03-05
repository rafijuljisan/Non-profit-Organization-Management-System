<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\SendWhatsAppReminderJob;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
// Previous auto-suspend command (Runs on the 1st of every month)
Schedule::command('members:auto-suspend')->monthlyOn(1, '00:00');

// 🟢 NEW: WhatsApp Reminder Job (Runs on the 5th of every month at 10:00 AM)
Schedule::job(new SendWhatsAppReminderJob)->monthlyOn(5, '10:00');