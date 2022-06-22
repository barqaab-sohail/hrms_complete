<?php

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('roles')->delete();

        $roles = array(
			array('name' => 'Super Admin', 'guard_name' => 'web'),
			array('name' => 'User', 'guard_name' => 'web'),   
		);

       \DB::table('roles')->insert($roles);
    }
}
