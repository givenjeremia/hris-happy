<?php

namespace Database\Seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class BpjsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'uuid' => Str::uuid(),
                'nominal' => 10.89,
                'type' => 'BPJS TOTAL',
                'created_at' => now(),
                'updated_at' => now(),
            ],
         
        ];

        DB::table('bpjs')->insert($roles);
    }
}
