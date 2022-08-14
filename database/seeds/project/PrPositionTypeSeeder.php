<?php

//namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PrPositionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('pr_position_types')->delete();
        
        $prPositionTypes = array(
        	array('name' => 'Key Personnel'),
        	array('name' => 'Non Key Personnel'),
        	array('name' => 'Direct Cost Personnel')
        );

        DB::table('pr_position_types')->insert($prPositionTypes);
    }
}
