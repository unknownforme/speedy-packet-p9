<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use App\Models\ShippingMethod;
use App\Models\DistributionLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $methods = DB::table('shipping_methods')->get();
        $locations = DB::table('distribution_locations')->get();

        if ($users->isEmpty() || $methods->isEmpty()) {
            return;
        }

        // Create a few dummy packages
        for ($i = 0; $i < 10; $i++) {
            $sender = $users->random();
            $receiver = $users->where('id', '!=', $sender->id)->random();
            $method = $methods->random();
            $location = $locations->random();

            $packageId = DB::table('packages')->insertGetId([
                'tracking_number' => 'SP-' . strtoupper(Str::random(8)),
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'shipping_method_id' => $method->id,
                'weight_grams' => rand(500, 5000),
                'shipping_cost_cents' => $method->price_cents,
                'current_status' => 'Bestelling geplaatst',
                'province' => 'Noord-Holland',
                'location_id' => $location->id,
                'qr_code_path' => 'qr/sample.png',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Add some history
            DB::table('package_status_history')->insert([
                'package_id' => $packageId,
                'status' => 'Bestelling geplaatst',
                'user_id' => $sender->id,
                'created_at' => now()->subDays(2),
            ]);

            DB::table('package_status_history')->insert([
                'package_id' => $packageId,
                'status' => 'Pakket klaargezet door verzender',
                'user_id' => $sender->id,
                'created_at' => now()->subDays(1),
            ]);
        }
    }
}
