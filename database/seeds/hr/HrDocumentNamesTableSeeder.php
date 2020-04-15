<?php

use Illuminate\Database\Seeder;

class HrDocumentNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hr_document_names')->delete();  
        $HrDocumentNames = array(
        	array('name' => 'Appointment Letter'),
        	array('name' => 'CNIC Front'),
        	array('name' => 'CNIC Back'),
        	array('name' => 'HR Form'),
            array('name' => 'Picture'),
        	array('name' => 'Joining Report'),
        	array('name' => 'Engineering Degree Graduation'),
        	array('name' => 'Engineering Degree MSc'),
        );
        DB::table('hr_document_names')->insert($HrDocumentNames);
    }
}
