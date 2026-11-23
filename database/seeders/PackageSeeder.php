<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Package;
use App\Models\Company;
use App\Models\ShippingMethod;
use App\Models\DistributionLocation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $companies = Company::all();
        $methods = DB::table("shipping_methods")->get();
        $locations = DB::table("distribution_locations")->get();

        if ($users->isEmpty() || $companies->isEmpty() || $methods->isEmpty()) {
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $company = $companies->random();
            $sender = $users->where("email", "!=", "admin@speedypacket.com")->where("email", "!=", "director@speedypacket.com")->random();
            $receiver = (rand(0, 1) === 1) ? $users->where("id", "!=", $sender->id)->random() : null;
            $method = $methods->random();
            $location = $locations->random();

            $packageId = DB::table("packages")->insertGetId([
                "tracking_number" => "SP-" . strtoupper(Str::random(8)),
                "company_id" => $company->id,
                "receiver_id" => $receiver ? $receiver->id : null,
                "shipping_method_id" => $method->id,
                "weight_grams" => rand(500, 5000),
                "shipping_cost_cents" => $method->price_cents,
                "current_status" => "Bestelling geplaatst",
                "destination_province" => Arr::random(["Noord-Holland", "Zuid-Holland", "Utrecht", "Noord-Brabant", "Limburg"]),
                "location_id" => $location->id,
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            DB::table("package_status_history")->insert([
                "package_id" => $packageId,
                "status" => "Bestelling geplaatst",
                "user_id" => $sender->id,
                "created_at" => now()->subDays(2),
            ]);

            DB::table("package_status_history")->insert([
                "package_id" => $packageId,
                "status" => "Pakket klaargezet door verzender",
                "user_id" => $sender->id,
                "created_at" => now()->subDays(1),
            ]);
        }
    }
}
