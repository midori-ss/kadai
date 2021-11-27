<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KadaiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('kadai')->insert([
            [
                'name' => '1年前期',
                'target' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => '2年前期',
                'target' => 2,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
