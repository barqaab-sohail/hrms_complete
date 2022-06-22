<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->delete();

       $permissions = array(
			array('name' => 'hr view record', 'guard_name' => 'web'),
			array('name' => 'hr add record', 'guard_name' => 'web'),
			array('name' => 'hr edit record', 'guard_name' => 'web'),
			array('name' => 'hr delete record', 'guard_name' => 'web'),	
		);

       DB::table('permissions')->insert($permissions);
    }
}
