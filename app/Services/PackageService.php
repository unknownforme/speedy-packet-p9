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

            $package = Package::create([
                'tracking_number' => 'SP-' . strtoupper(Str::random(8)),
                'company_id' => $data['company_id'],
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
                'location_id' => null,
            ]);

            // Create the first history entry
            // We assume the sender is the company, but we need a user to record the action in history
            // We'll use the sender_id passed in the request (which is the logged-in user)
            $package->statusHistory()->create([
                'status' => 'Bestelling geplaatst',
                'user_id' => $data['sender_id'] ?? null,
                'created_at' => now(),
            ]);

            return $package;
        });
    }
}
