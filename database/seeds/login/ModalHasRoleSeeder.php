<?php

use Illuminate\Database\Seeder;

class ModalHasRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('model_has_roles')->delete();

        $modelHasRoles = array(
			array('role_id' => '1', 'model_type' => 'App\User','model_id'=>'1')
		);

       \DB::table('model_has_roles')->insert($modelHasRoles);
    }
}
