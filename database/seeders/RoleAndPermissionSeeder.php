<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = [
            "Admin",
            "Directeur",
            "Verzender",
            "Pakket-ontvanger",
            "Distributiemedewerker",
            "Koerier",
        ];

        foreach ($roles as $role) {
            Role::create(["name" => $role]);
        }
    }
}