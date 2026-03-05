<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $guarded = [];

    // প্রজেক্টটি কোন জেলার
    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }

    // প্রজেক্টের জন্য আসা সব অনুদান
    public function donations(): HasMany
    {
        return $this->hasMany(Donation::class);
    }

    // প্রজেক্টের সব খরচ
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }
}