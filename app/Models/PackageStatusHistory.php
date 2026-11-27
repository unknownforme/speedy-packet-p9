<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageStatusHistory extends Model
{
    protected $table = 'package_status_history';
    protected $fillable = ['package_id', 'status', 'user_id', 'notes', 'created_at'];
    public $timestamps = false; // We manually handle created_at
    
    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
