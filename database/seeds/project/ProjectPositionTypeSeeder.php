<?php

use Illuminate\Database\Seeder;

class ProjectPositionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('pr_position_types')->delete();
        
        $prPositions = array(
        	array('name' => 'Key Personnel'),
            array('name' => 'Non Key Personnel'),
        	array('name' => 'Direct Cost'),
        );

        DB::table('pr_position_types')->insert($prPositions);
    }
}
