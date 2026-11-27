<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Package extends Model
{
    protected $fillable = [
        'tracking_number',
        'company_name',
        'receiver_id',
        'receiver_email',
        'receiver_street',
        'receiver_city',
        'receiver_house_number',
        'receiver_house_number_suffix',
        'shipping_method_id',
        'weight_grams',
        'shipping_cost_cents',
        'current_status',
        'destination_province',
        'destination_street',
        'house_number',
        'house_number_suffix',
        'assigned_to_user_id'
    ];

    protected $casts = [
        'tracking_number' => 'integer',
    ];

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function shippingMethod(): BelongsTo
    {
        return $this->belongsTo(ShippingMethod::class);
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(PackageStatusHistory::class);
    }

    public function packageReturn(): HasOne
    {
        return $this->hasOne(PackageReturn::class);
    }

    /**
     * Determine the package type based on weight.
     */
    public function getPackageTypeAttribute(): string
    {
        if ($this->weight_grams < 500) {
            return 'Small';
        } elseif ($this->weight_grams < 2000) {
            return 'Medium';
        } else {
            return 'Large';
        }
    }

    /**
     * Returns the QR code URL for the tracking number.
     */
    public function getQrCodeUrlAttribute(): string
    {
        return "/qr-code/{$this->tracking_number}";
    }
}
