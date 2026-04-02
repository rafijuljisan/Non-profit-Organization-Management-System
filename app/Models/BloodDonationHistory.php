<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloodDonationHistory extends Model
{
    protected $fillable = ['user_id', 'donation_date'];

    public function donor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}