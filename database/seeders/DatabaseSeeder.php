<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesAndPermissionsSeeder::class);
        $this->call(UsersTableSeeder::class);

        // assign admin role to admin user
        $user = User::Where('username', '=', 'chris')->first();
        $role = Role::Where('name', '=', 'admin')->first();
        $user->roles()->attach($role->id);
    }
}
