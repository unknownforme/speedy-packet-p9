<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnRequest extends Model
{
    protected $fillable = ['package_id', 'requested_at', 'reason', 'status'];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
