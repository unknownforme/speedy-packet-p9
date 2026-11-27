<?php

namespace App\Services;

use App\Models\Package;
use App\Models\ShippingMethod;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class PackageService
{
    /**
     * Create a new package.
     *
     * @param array $data
     * @return Package
     */
    public function createPackage(array $data): Package
    {
        return DB::transaction(function () use ($data) {
            $method = ShippingMethod::findOrFail($data['shipping_method_id']);

            // Dynamic Linking: Check if the receiver email matches an existing user
            $receiverId = null;
            if (!empty($data['receiver_email'])) {
                $existingUser = User::where('email', $data['receiver_email'])->first();
                if ($existingUser) {
                    $receiverId = $existingUser->id;
                }
            }

            // Generate a unique tracking number (integer)
            do {
                $trackingNumber = random_int(10000000, 99999999);
            } while (Package::where('tracking_number', $trackingNumber)->exists());

            $package = Package::create([
                'tracking_number' => $trackingNumber,
                'company_name' => $data['company_name'],
                'receiver_id' => $receiverId,
                'receiver_email' => $data['receiver_email'] ?? null,
                'receiver_street' => $data['receiver_street'] ?? null,
                'receiver_city' => $data['receiver_city'] ?? null,
                'receiver_house_number' => $data['receiver_house_number'] ?? null,
                'receiver_house_number_suffix' => $data['receiver_house_number_suffix'] ?? null,
                'shipping_method_id' => $method->id,
                'weight_grams' => $data['weight_grams'],
                'shipping_cost_cents' => $method->price_cents,
                'current_status' => 'Bestelling geplaatst',
                'destination_province' => $data['destination_province'],
                'destination_street' => $data['receiver_street'] ?? null,
                'house_number' => $data['receiver_house_number'] ?? null,
                'house_number_suffix' => $data['receiver_house_number_suffix'] ?? null,
            ]);

            // Create the first history entry
            $package->statusHistory()->create([
                'status' => 'Bestelling geplaatst',
                'user_id' => $data['sender_id'] ?? null,
                'created_at' => now(),
            ]);

            return $package;
        });
    }

    /**
     * Record a delivery outcome from the courier.
     *
     * @param Package $package
     * @param string $outcome
     * @param string|null $reason
     * @return void
     */
    public function recordDeliveryOutcome(Package $package, string $outcome, string $reason = null): void
    {
        $status = 'Geleverd';
        $note = '';

        if ($outcome === 'delivered_recipient') {
            $note = 'Direct aan de ontvanger geleverd.';
        } elseif ($outcome === 'delivered_neighbor') {
            $note = 'Bij de buren afgeleverd.';
        } elseif ($outcome === 'delivered_pickup') {
            $note = 'Bij een afhaalpunt afgeleverd.';
        } elseif ($outcome === 'failed') {
            $status = 'Mislukte levering';
            $note = 'Levering mislukt. ' . ($reason ? 'Reden: ' . $reason : '');
        }

        // Record the status change in history
        $package->statusHistory()->create([
            'status' => $status,
            'user_id' => auth()->id(),
            'notes' => $note,
            'created_at' => now(),
        ]);

        $package->update(['current_status' => $status]);
    }
}
