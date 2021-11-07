<?php

namespace Database\Seeders;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

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

        Permission::create(['name' => 'admin view graphs']);

        Permission::create(['name' => 'view groups']);
        Permission::create(['name' => 'create groups']);
        Permission::create(['name' => 'update groups']);
        Permission::create(['name' => 'delete groups']);

        Permission::create(['name' => 'view logs']);

        Permission::create(['name' => 'view scans']);
        Permission::create(['name' => 'update scans']);

        Permission::create(['name' => 'view sessions']);

        Permission::create(['name' => 'view users']);
        Permission::create(['name' => 'create users']);
        Permission::create(['name' => 'update users']);
        Permission::create(['name' => 'delete users']);

        // create the admin role
        $role = Role::create(['name' => 'admin']);
        $role->givePermissionTo(Permission::all());

        // create the basic user role
        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo('login');

    }
}
