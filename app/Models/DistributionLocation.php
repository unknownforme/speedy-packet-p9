<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DistributionLocation extends Model
{
    protected $fillable = ['name', 'province'];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class);
    }
}
