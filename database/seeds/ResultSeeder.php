<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class ResultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('results')->delete();  
        $subStatuses = array(
        	array('name' => 'Under Evaluation'),
        	array('name' => 'Qualify'),
        	array('name' => 'Won by BARQAAB'),
        	array('name' => 'Won by Others'),
        );
        DB::table('results')->insert($subStatuses);
    }
}
