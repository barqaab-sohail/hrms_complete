<?php

use Illuminate\Database\Seeder;

class CostTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cost_types')->delete();  
        $costTypes = array(
        	array('name' => 'Salary Cost'),
        	array('name' => 'Direct Cost'),
        	array('name' => 'Both'),
        );
        DB::table('cost_types')->insert($costTypes);
    }
}
