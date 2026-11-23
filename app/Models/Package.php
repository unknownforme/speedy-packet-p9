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
        'company_id',
        'receiver_id',
        'shipping_method_id',
        'weight_grams',
        'shipping_cost_cents',
        'current_status',
        'destination_province',
        'location_id',
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

    public function location(): BelongsTo
    {
        return $this->belongsTo(DistributionLocation::class, 'location_id');
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(PackageStatusHistory::class);
    }

    public function returnRequest(): HasOne
    {
        return $this->hasOne(ReturnRequest::class);
    }

    /**
     * Dynamically generate the QR code based on tracking number.
     */
    public function getQrCodeUrlAttribute(): string
    {
        // In a real app, this would return a URL to a controller/route 
        // that generates the QR code image.
        return "/qr-code/{$this->tracking_number}";
    }
}
