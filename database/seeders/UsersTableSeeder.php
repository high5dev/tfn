<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Chris',
            'email' => 'chris@comgw.co.uk',
            'password' => bcrypt('letmein1234')
        ]);

    }
}
