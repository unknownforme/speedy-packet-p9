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

        $faker = \Faker\Factory::create();
        $provinces = ["Groningen", "Friesland", "Drenthe", "Overijssel", "Flevoland", "Gelderland", "Utrecht", "Noord-Holland", "Zuid-Holland", "Zeeland", "Noord-Brabant", "Limburg"];

        for ($i = 0; $i < 15; $i++) {
            $company = $companies->random();
            $sender = $users->where("email", "!=", "admin@speedypacket.com")->where("email", "!=", "director@speedypacket.com")->random();
            
            // Randomly decide if the receiver is a registered user
            $receiver = (rand(0, 1) === 1) ? $users->where("id", "!=", $sender->id)->random() : null;
            
            $method = $methods->random();
            $location = $locations->isEmpty() ? null : $locations->random();

            $receiverEmail = $receiver ? $receiver->email : $faker->email();
            
            // Generate granular receiver address
            $receiverStreet = $faker->streetName();
            $receiverCity = $faker->city();
            $receiverHouseNumber = $faker->numberBetween(1, 200);
            $receiverHouseNumberSuffix = $faker->optional()->lexify('??');

            $packageId = DB::table("packages")->insertGetId([
                "tracking_number" => rand(10000000, 99999999),
                "company_id" => $company->id,
                "receiver_id" => $receiver ? $receiver->id : null,
                "receiver_email" => $receiverEmail,
                "receiver_street" => $receiverStreet,
                "receiver_city" => $receiverCity,
                "receiver_house_number" => $receiverHouseNumber,
                "receiver_house_number_suffix" => $receiverHouseNumberSuffix,
                "shipping_method_id" => $method->id,
                "weight_grams" => rand(500, 5000),
                "shipping_cost_cents" => $method->price_cents,
                "current_status" => "Bestelling geplaatst",
                "destination_province" => Arr::random($provinces),
                "destination_street" => $faker->streetName(),
                "house_number" => $faker->numberBetween(1, 200),
                "house_number_suffix" => $faker->optional()->lexify('??'),
                "location_id" => $location ? $location->id : null,
                "created_at" => now(),
                "updated_at" => now(),
            ]);

            DB::table("package_status_history")->insert([
                "package_id" => $packageId,
                "status" => "Bestelling geplaatst",
                "created_at" => now()->subDays(2),
            ]);
        }
    }
}
