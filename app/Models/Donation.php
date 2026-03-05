<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Donation extends Model
{
    use HasFactory, LogsActivity;

    protected $guarded = [];

    // ✅ Required method for LogsActivity trait
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()                          // সব কলাম লগ করবে
            ->logOnlyDirty()                    // শুধু পরিবর্তিত ডেটা লগ করবে
            ->dontSubmitEmptyLogs()             // খালি লগ সেভ করবে না
            ->setDescriptionForEvent(fn (string $eventName) => "Donation {$eventName}");
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}