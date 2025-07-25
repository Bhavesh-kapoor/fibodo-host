<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create permissions
        $permissions = array_merge(...array_values(config('permissions.host')));
        $hostPermissions = config('permissions.host');

        $permissionsToCreate = array_map(function ($permission) {
            return [
                'id' => Str::ulid(),
                'name' => $permission,
                'guard_name' => 'api',
            ];
        }, $permissions);

        Permission::insertOrIgnore($permissionsToCreate);

        // create Roles 
        $superAdminRole = Role::factory()->create(['name' => 'super-admin', 'guard_name' => 'api']);
        $adminRole = Role::factory()->create(['name' => 'admin', 'guard_name' => 'api']);
        $hostRole = Role::factory()->create(['name' => 'host', 'guard_name' => 'api']);
        $clientRole = Role::factory()->create(['name' => 'client', 'guard_name' => 'api']);

        // Assign all permissions to the admin role
        $superAdminRole->syncPermissions($permissions);
        $adminRole->syncPermissions($permissions);

        // Assign limited permissions to the host role
        $hostRole->syncPermissions($hostPermissions);

        // Assign limited permissions to the client role
        //$clientRole->syncPermissions(['view products']);
    }
}
