<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    protected $guarded = [];

    // Users with this designation
    public function users()
    {
        return $this->hasMany(User::class, 'designation', 'name');
    }
}