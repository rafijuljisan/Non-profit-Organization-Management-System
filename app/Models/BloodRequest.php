<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodRequest extends Model
{
    protected $fillable = [
        'donor_id',
        'requester_name',
        'requester_phone',
        'patient_name',
        'hospital_name',
        'bags_needed',
        'status',
        'note' // 'note' যুক্ত করা হলো
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'donor_id');
    }
}