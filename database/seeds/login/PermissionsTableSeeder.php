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
			array('name' => 'pr edit document', 'guard_name' => 'web'),
            array('name' => 'hr edit posting', 'guard_name' => 'web'),
            array('name' => 'hr edit promotion', 'guard_name' => 'web'),
            array('name' => 'hr all employees', 'guard_name' => 'web'),
            array('name' => 'hr active employees', 'guard_name' => 'web'),
            array('name' => 'asset edit record', 'guard_name' => 'web'),
            array('name' => 'hr view graph', 'guard_name' => 'web'),
            array('name' => 'hr view documentation', 'guard_name' => 'web'),
            array('name' => 'pr view water', 'guard_name' => 'web'),
            array('name' => 'pr view power', 'guard_name' => 'web'),
            array('name' => 'pr add water', 'guard_name' => 'web'),
            array('name' => 'pr add power', 'guard_name' => 'web'),
            array('name' => 'pr view progress', 'guard_name' => 'web'),
            array('name' => 'pr view invoice', 'guard_name' => 'web'),
            array('name' => 'pr view documentation', 'guard_name' => 'web'),
            array('name' => 'hr edit employee information', 'guard_name' => 'web'),
            array('name' => 'pr limited access', 'guard_name' => 'web'),
            array('name' => 'lev edit record', 'guard_name' => 'web'),
            array('name' => 'asset all record', 'guard_name' => 'web'),
            array('name' => 'hr add designation', 'guard_name' => 'web'),
            array('name' => 'hr edit salary', 'guard_name' => 'web'),
            array('name' => 'hr view appointment', 'guard_name' => 'web'),
            array('name' => 'hr view contact', 'guard_name' => 'web'),
            array('name' => 'hr view education', 'guard_name' => 'web'),
            array('name' => 'hr view experience', 'guard_name' => 'web'),
            array('name' => 'hr view promotion', 'guard_name' => 'web'),
            array('name' => 'hr view posting', 'guard_name' => 'web'),
            array('name' => 'asset delete record', 'guard_name' => 'web'),
		);

       DB::table('permissions')->insert($permissions);
    }
}
