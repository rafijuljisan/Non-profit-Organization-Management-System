<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded = [];

    // চাঁদাটি কোন সদস্যের
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // কে পেমেন্ট অ্যাপ্রুভ করেছে
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}