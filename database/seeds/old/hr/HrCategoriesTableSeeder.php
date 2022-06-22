<?php

use Illuminate\Database\Seeder;

class HrCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('hr_categories')->delete();  
        $hrCategogires = array(
        	array('name' => 'A'),
        	array('name' => 'B'),
        	array('name' => 'C'),
        );
        DB::table('hr_categories')->insert($hrCategogires);
    }
}
