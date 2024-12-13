<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        

        DB::table('users')->insert([
            ['name' => 'John Doe', 'email' => 'admin@admin.com', 'password' => Hash::make('Lights123!'), 'roles_id' => '1', 'status' => 'active',],
        ]);

        // $user = User::create([
        //     'name' => 'John Doe',
        //     'email' => 'admin@admin.com',
        //     'password' => Hash::make('Lights123!'),
        //     'roles_id' => '1', // Make sure to set a secure password
        // ]);
    }
}
