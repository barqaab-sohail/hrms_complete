<?php

//namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PrCostTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('pr_cost_types')->delete();
        
        $PrRoles = array(
        	array('name' => 'Original Cost'),
        	array('name' => 'Amendment Cost') 
        );

        \DB::table('pr_cost_types')->insert($PrRoles);
    }
}
