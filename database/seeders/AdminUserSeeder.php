<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->where('email', 'admin@research-internship.com')->delete();

        DB::table('users')->insert([
            'name'       => 'Admin',
            'email'      => 'admin@research-internship.com',
            'password'   => Hash::make('123456'),
            'role'       => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}