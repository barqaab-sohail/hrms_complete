<?php

use Illuminate\Database\Seeder;

class LoginSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
	       PermissionsTableSeeder::class,
	        RoleSeeder::class,
	        UserTableSeeder::class,
	        ModalHasRoleSeeder::class,
        ]);
    }
}
