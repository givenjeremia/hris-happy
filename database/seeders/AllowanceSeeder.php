<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AllowanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Transportasi',
                'desc' => 'Tunjangan Transportasi',
                'nominal' => 17500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Makan',
                'desc' => 'Tunjangan Makan',
                'nominal' => 22500,
                'created_at' => now(),
                'updated_at' => now(),
            ],
         
        ];

        DB::table('allowances')->insert($roles);
    }
}
