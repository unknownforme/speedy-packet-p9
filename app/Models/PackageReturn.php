<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageReturn extends Model
{
    protected $fillable = [
        'package_id',
        'requested_at',
        'reason',
        'status',
    ];

    /**
     * Get the package associated with the return.
     *
     * @return BelongsTo
     */
    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }
}
