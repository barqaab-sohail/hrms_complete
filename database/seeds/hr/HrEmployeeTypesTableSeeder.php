<?php

use Illuminate\Database\Seeder;

class HrEmployeeTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hr_employee_types')->delete();  
        $hrEmployeeTypes = array(
        	array('name' => 'Professionals'),
        	array('name' => 'Para Professionals'),
        	array('name' => 'Non Technical'),
        );
        DB::table('hr_employee_types')->insert($hrEmployeeTypes);
    }
}
