<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'description' => 'Administrator role with full access'],
            ['name' => 'broker', 'description' => 'Regular user role with limited access'],
            ['name' => 'seller', 'description' => 'Moderator role with some management access'],
            ['name' => 'buyer', 'description' => 'Moderator role with some management access'],
            ['name' => 'employee', 'description' => 'Guest role with view-only access'],
        ]);
    }
}
