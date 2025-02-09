<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
$karyawanRole = Role::where('name', 'karyawan')->first();

User::create([
    'name' => 'Admin User',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role_id' => $adminRole->id,
]);

User::create([
    'name' => 'Karyawan User',
    'email' => 'karyawan@example.com',
    'password' => bcrypt('password'),
    'role_id' => $karyawanRole->id,
]);
    }
}
