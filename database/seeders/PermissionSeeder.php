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
            'view_rdv',
            'manage_rdv',
            'manage_clients',
            'manage_disponibilites',
            'manage_payments',
            'view_dashboard',
            'manage_roles'
        ];

        foreach ($permissions as $p) {
            Permission::create(['name' => $p]);
        }
    }
}
