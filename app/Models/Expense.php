<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $guarded = [];

    // খরচটি কোন প্রজেক্টের (যদি থাকে)
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    // কে খরচ এন্ট্রি করেছে
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // কে খরচ অ্যাপ্রুভ করেছে
    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}