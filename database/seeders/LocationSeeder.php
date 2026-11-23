<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $locations = [
            ['name' => 'Centraal Hub - Aisle 1', 'province' => 'Noord-Holland'],
            ['name' => 'Centraal Hub - Aisle 2', 'province' => 'Noord-Holland'],
            ['name' => 'Regio West - Sector A', 'province' => 'Zuid-Holland'],
            ['name' => 'Regio West - Sector B', 'province' => 'Zuid-Holland'],
            ['name' => 'Regio Oost - Sector C', 'province' => 'Utrecht'],
            ['name' => 'Regio Zuid - Sector D', 'province' => 'Noord-Brabant'],
        ];

        foreach ($locations as $location) {
            DB::table('distribution_locations')->updateOrInsert(['name' => $location['name']], $location);
        }
    }
}
