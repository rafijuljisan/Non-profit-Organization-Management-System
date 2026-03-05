<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class District extends Model
{
    use HasFactory;

    protected $guarded = [];

    // জেলার কো-অর্ডিনেটর/এডমিন
    public function coordinator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coordinator_id');
    }

    // এই জেলার অধীনে থাকা সব সদস্য
    public function members(): HasMany
    {
        return $this->hasMany(User::class, 'district_id');
    }

    // এই জেলার অধীনে চলমান প্রজেক্টগুলো
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}