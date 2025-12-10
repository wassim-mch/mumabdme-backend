<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('role_permission')->insert([
            [
                'role_id' => 1, 
                'permission_id' => 1, 
            ],
            [
                'role_id' => 1,
                'permission_id' => 2,
            ],
            [
                'role_id' => 1,
                'permission_id' => 3, 
            ],
        ]);
        DB::table('role_permission')->insert([
            [
                'role_id' => 2, 
                'permission_id' => 4,
            ],
        ]);
    }
}
