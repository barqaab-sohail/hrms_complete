<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class SubStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sub_statuses')->delete();  
        $subStatuses = array(
        	array('name' => 'Submitted and Under Evaluation'),
        	array('name' => 'Qualify'),
            array('name' => 'Canceled'),
        	array('name' => 'Won by BARQAAB'),
        	array('name' => 'Won by Others'),
        );
        DB::table('sub_statuses')->insert($subStatuses);
    }
}
