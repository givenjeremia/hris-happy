<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        $admin = User::create([
            'name'     => 'Admin',
            'email'    => 'admin@admin.com',
            'password' => bcrypt('p@ssw0rd'),
        ]);

        $admin->assignRole('admin');

        // $ferdi = User::create([
        //     'name'     => 'Ferdi',
        //     'email'    => 'ferdi@gmailcom',
        //     'password' => bcrypt('p@ssw0rd'),
        // ]);

        // $ferdi->assignRole('employee');

        // $sambo = User::create([
        //     'name'     => 'Sambo',
        //     'email'    => 'sambo@gmailcom',
        //     'password' => bcrypt('p@ssw0rd'),
        // ]);

        // $sambo->assignRole('employee');

        // $hendra = User::create([
        //     'name'     => 'Hendra',
        //     'email'    => 'hendra@gmailcom',
        //     'password' => bcrypt('p@ssw0rd'),
        // ]);

        // $hendra->assignRole('employee');
    }
}
