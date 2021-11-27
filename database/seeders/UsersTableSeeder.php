<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'name' => '門澤',
                'email' => 'admin@test.com',
                'email_verified_at' => now(),
                'code' => '99999',
                'password' => bcrypt('password'),
                'role' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '一般',
                'email' => 'user@test.com',
                'email_verified_at' => now(),
                'code' => '11111',
                'password' => bcrypt('password'),
                'role' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
