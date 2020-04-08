<?php

use Illuminate\Database\Seeder;

class PrRolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_roles')->delete();
        
        $PrRoles = array(
        	array('name' => 'Independent'),
        	array('name' => 'JV Partner'),
        	array('name' => 'Lead Partner'),
        	array('name' => 'Sub Consultant'),
        );

        DB::table('pr_roles')->insert($PrRoles);
    }
}
