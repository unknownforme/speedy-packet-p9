<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['name', 'is_occupied'];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class, 'current_location_id');
    }

    public function trackingHistories(): HasMany
    {
        return $this->hasMany(TrackingHistory::class, 'location_id');
    }
}
