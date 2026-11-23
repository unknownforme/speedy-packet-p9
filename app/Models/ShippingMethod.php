<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShippingMethod extends Model
{
    protected $fillable = ['name', 'price_cents', 'is_trackable'];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }
}
