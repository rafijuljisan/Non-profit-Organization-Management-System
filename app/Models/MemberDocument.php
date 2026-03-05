<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemberDocument extends Model
{
    use HasFactory;

    protected $guarded = [];

    // ডকুমেন্টটি কোন সদস্যের
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}