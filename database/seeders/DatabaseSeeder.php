<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        DB::table('category')->insert([
            [
                'name' => 'Fiksi',
                'created_at' => now()
            ],
            [
                'name' => 'Non Fiksi',
                'created_at' => now()
            ]
        ]);

        DB::table('publisher')->insert([
            [
                'name' => 'Gramedia',
                'created_at' => now()
            ],
            [
                'name' => 'Erlangga',
                'created_at' => now()
            ],
            [
                'name' => 'Mizan',
                'created_at' => now()
            ],
        ]);
    }
}
