<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@speedypacket.com',
            'password' => Hash::make('admin123'),
        ]);
        $admin->assignRole('Admin');

        // Directeur User
        $directeur = User::create([
            'name' => 'Director Name',
            'email' => 'director@speedypacket.com',
            'password' => Hash::make('director123'),
        ]);
        $directeur->assignRole('Directeur');

        // Create random users for other roles
        $roles = ['Verzender', 'Pakket-ontvanger', 'Distributiemedewerker', 'Koerier'];
        
        foreach ($roles as $role) {
            for ($i = 0; $i < 3; $i++) {
                $user = User::create([
                    'name' => ucfirst($role) . " User " . ($i + 1),
                    'email' => strtolower($role) . "{$i}@example.com",
                    'password' => Hash::make('password123'),
                ]);
                $user->assignRole($role);
            }
        }
    }
}
