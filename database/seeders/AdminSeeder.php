<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat role 'admin' jika belum ada
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Buat user admin jika belum ada
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@template.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@template.com',
                'password' => Hash::make('password'),
            ]
        );

        // Assign role admin ke user
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }

        // Discover permissions (jika menggunakan Spatie Permission Auto-Discovery)
        Artisan::call('permission:discover');
    }
}
