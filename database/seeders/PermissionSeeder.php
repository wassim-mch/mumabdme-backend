<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage_services',
            'manage_categories',
            'manage_roles', 
            'manage_rdv',
            'manage_rdvs_own',
            'manage_users',
        ];

        foreach ($permissions as $p) {
            Permission::create(['name' => $p]);
        }
    }
}
