<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('users')->delete();

        \DB::table('users')->insert([
            'user_status' => 1,
            'email' => 'hrms@hrms.com',
            'password' => bcrypt('great786'),
        ]);
    }
}
