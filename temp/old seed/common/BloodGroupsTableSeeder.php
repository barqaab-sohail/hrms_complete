<?php

use Illuminate\Database\Seeder;

class BloodGroupsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
    	DB::table('blood_groups')->delete();  
        $BloodGroups = array(
        	array('name' => 'A+'),
        	array('name' => 'O+'),
        	array('name' => 'B+'),
        	array('name' => 'AB+'),
        	array('name' => 'A-'),
        	array('name' => 'O-'),
        	array('name' => 'B-'),
        	array('name' => 'AB-'),
        );
        DB::table('blood_groups')->insert($BloodGroups);
       
    }
}
