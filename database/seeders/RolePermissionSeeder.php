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
                'role_id' => 1, // Admin
                'permission_id' => 1, // create_user
            ],
            [
                'role_id' => 1,
                'permission_id' => 2, // edit_user
            ],
            [
                'role_id' => 1,
                'permission_id' => 4, // view_reports
            ],
        ]);
        DB::table('role_permission')->insert([
            [
                'role_id' => 2, // User
                'permission_id' => 4, // view_reports
            ],
        ]);
    }
}
