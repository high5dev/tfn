<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()['cache']->forget('spatie.permission.cache');

        // create permissions
        Permission::create(['name' => 'login']);

        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        Permission::create(['name' => 'view logs']);

        // create the admin role
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        // create the basic user role
        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo('login');

    }
}
