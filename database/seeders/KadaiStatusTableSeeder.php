<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KadaiStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kadai_status')->insert([
            [
                'kadai_id' => 1,
                'user_code' => 2,
                'status' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'kadai_id' => 2,
                'user_code' => 3,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
