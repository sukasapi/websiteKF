<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class RoleAndAdminSeeder extends Seeder
{
    /**
     * Buat role Admin & Editor, lalu user Admin awal.
     */
    public function run(): void
    {
        // Buat role
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        Role::firstOrCreate(['name' => 'Editor']);

        // Buat user admin awal
        $user = User::firstOrCreate(
            ['email' => 'admin@kurniafedora.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
            ]
        );

        $user->assignRole($admin);
    }
}
