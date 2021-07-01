<?php

use Illuminate\Database\Seeder;

class AsPurchaseConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('as_purchase_conditions')->delete();  
        $asPurchaseConditions = array(
        	array('name' => 'New'),
        	array('name' => 'Used'),
        );
        DB::table('as_purchase_conditions')->insert($asPurchaseConditions);
    }
}
