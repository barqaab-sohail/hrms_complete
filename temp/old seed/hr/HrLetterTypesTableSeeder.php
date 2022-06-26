<?php

use Illuminate\Database\Seeder;

class HrLetterTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hr_letter_types')->delete();  
        $HrLetterTypes = array(
        	array('name' => 'One Page without Notice'),
        	array('name' => 'Two Page with Notice'),
        );
        DB::table('hr_letter_types')->insert($HrLetterTypes);
    }
}
