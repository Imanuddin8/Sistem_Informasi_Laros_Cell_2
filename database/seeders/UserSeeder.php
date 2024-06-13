<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            'nama' => 'Imanuddin',
            'username' => 'iman',
            'password' => bcrypt('1234'),
            'role' => 'admin'
        ]);
        DB::table('users')->insert([
            'nama' => 'Asep Surya',
            'username' => 'asep',
            'password' => bcrypt('1234'),
            'role' => 'karyawan'
        ]);
    }
}
