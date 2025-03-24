<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            ['name' => 'Admin', 'guard_name' => 'web'],
            ['name' => 'Mentor', 'guard_name' => 'web'],
            ['name' => 'Étudiant', 'guard_name' => 'web'],
        ];

        DB::table('roles')->insert($roles);
    }
}
