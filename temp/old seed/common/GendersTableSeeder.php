<?php

use Illuminate\Database\Seeder;

class GendersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('genders')->delete();
        
        $Genders = array(
        	array('name' => 'Male'),
        	array('name' => 'Femail'),
        );

         DB::table('genders')->insert($Genders);
    }
}
