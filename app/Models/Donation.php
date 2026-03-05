<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Donation extends Model
{
    use HasFactory;

    protected $guarded = [];

    // অনুদানটি কোন প্রজেক্টের জন্য (যদি থাকে)
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}