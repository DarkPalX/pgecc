<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'superadmin@pgecc.com'], [
            'name' => 'Super Admin',
            'role' => 'super_admin',
            'email' => 'superadmin@pgecc.com',
            'password' => Hash::make('password'), // Always hash the password!
        ]);
    }
}
