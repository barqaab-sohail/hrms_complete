<?php

use Illuminate\Database\Seeder;

class PrCostTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_cost_types')->delete();  
        $prCostTypes = array(
        	array('name' => 'Original Cost'),
        	array('name' => 'Amendment Cost'),
        );
        DB::table('pr_cost_types')->insert($prCostTypes);
    }
}
