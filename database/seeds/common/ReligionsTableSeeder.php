<?php

use Illuminate\Database\Seeder;

class ReligionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('religions')->delete();  
        $Reeligions = array(
        	array('name' => 'Islam'),
        	array('name' => 'Christianity'),
        	array('name' => 'Hindu'),
        	array('name' => 'Sikh'),
        	
        );
        DB::table('religions')->insert($Reeligions);    }
}
