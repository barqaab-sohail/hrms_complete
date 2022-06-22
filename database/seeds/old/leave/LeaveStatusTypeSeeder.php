<?php

use Illuminate\Database\Seeder;

class LeaveStatusTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('le_status_types')->delete();  
        $leStatusTypes = array(
        	array('name' => 'Approved'),
            array('name' => 'Rejected')
        );
        DB::table('le_status_types')->insert($leStatusTypes);
    }
}
