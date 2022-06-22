<?php

use Illuminate\Database\Seeder;

class AsConditionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('as_condition_types')->delete();  
        $asConditionTypes = array(
        	array('name' => 'Working'),
        	array('name' => 'Repairable'),
        	array('name' => 'Not Repairable'),
        );
        DB::table('as_condition_types')->insert($asConditionTypes);
    }
}
