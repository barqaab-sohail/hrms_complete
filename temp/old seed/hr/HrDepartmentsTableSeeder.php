<?php

use Illuminate\Database\Seeder;

class HrDepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hr_departments')->delete();  
        $HrDepartments = array(
        	array('name' => 'Finance'),
        	array('name' => 'Power'),
        	array('name' => 'Water'),
        );
        DB::table('hr_departments')->insert($HrDepartments);
    }
}
