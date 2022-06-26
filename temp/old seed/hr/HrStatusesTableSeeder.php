<?php

use Illuminate\Database\Seeder;

class HrStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hr_statuses')->delete();
        
        $HrStatuses = array(
        	array('name' => 'On Board'),
        	array('name' => 'Resigned'),
        	array('name' => 'Terminated'),
        	array('name' => 'Retired'),
        	array('name' => 'Long Leave'),
        	array('name' => 'Manmonth Ended'),

        );

         DB::table('hr_statuses')->insert($HrStatuses);
    }
}
