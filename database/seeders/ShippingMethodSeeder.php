<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shipping_methods')->updateOrInsert(['name' => 'Envelop'], ['price_cents' => 285, 'is_trackable' => true]);
        DB::table('shipping_methods')->updateOrInsert(['name' => 'Brievenbuspakket'], ['price_cents' => 375, 'is_trackable' => false]);
        DB::table('shipping_methods')->updateOrInsert(['name' => 'Standaard'], ['price_cents' => 395, 'is_trackable' => true]);
        DB::table('shipping_methods')->updateOrInsert(['name' => 'Spoed'], ['price_cents' => 525, 'is_trackable' => true]);
    }
}
