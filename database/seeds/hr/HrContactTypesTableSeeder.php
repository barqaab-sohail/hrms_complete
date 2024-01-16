<?php

use Illuminate\Database\Seeder;

class HrContactTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hr_contact_types')->delete();
        
        $HrContactTypes = array(
        	array('name' => 'Permanent'),
        	array('name' => 'Present'),
        );

         DB::table('hr_contact_types')->insert($HrContactTypes);
    }
}
