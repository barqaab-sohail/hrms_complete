<?php

use Illuminate\Database\Seeder;

class EducationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('educations')->delete();

       $educations = array(
			array('degree_name' => 'B.Sc Civil Engineering', 'level' => 16),
			array('degree_name' => 'M.Sc Civil Engineering', 'level' => 18),
			array('degree_name' => 'B.Sc Electrical Engineering', 'level' => 16),
			array('degree_name' => 'M.Sc Electrical Engineering', 'level' => 18),
			
		);

       DB::table('educations')->insert($educations);
    }
}
