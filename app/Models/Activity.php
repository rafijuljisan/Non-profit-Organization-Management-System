<?php

namespace App\Models;

use Spatie\Activitylog\Models\Activity as SpatieActivity;
use Illuminate\Support\Collection;

class Activity extends SpatieActivity
{
    // Force properties to always return a Collection
    public function getPropertiesAttribute($value): Collection
    {
        if (is_string($value)) {
            return collect(json_decode($value, true) ?? []);
        }
        return collect($value ?? []);
    }
}