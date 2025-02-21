<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate([
            'name' => 'Admin',
            'email' => 'admin@example.com',
        ], [
            'password' => Hash::make('admin123'), // Ganti sesuai kebutuhan
        ]);

        // Buat user biasa
        $writer = User::firstOrCreate([
            'name' => 'User',
            'email' => 'user@example.com',
        ], [
            'password' => Hash::make('writer123'),
        ]);

        // Assign role ke user
        $admin->assignRole("admin");
        $writer->assignRole('writer');
    }
}
