<?php

use Illuminate\Database\Seeder;

class HrGradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hr_grades')->delete();  
        $hrGrades = array(
        	array('name' => '1'),
        	array('name' => '2'),
        	array('name' => '3'),
        	array('name' => '4'),
        	array('name' => '5'),
        	array('name' => '6'),
        	array('name' => '7'),
        	array('name' => '8'),
        	array('name' => '9'),
        	array('name' => '10'),
        	array('name' => '11'),
        	array('name' => '12'),
        	array('name' => '13'),
        	array('name' => '14'),
        );
        DB::table('hr_grades')->insert($hrGrades);
    }
}
