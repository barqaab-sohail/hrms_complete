<?php

use Illuminate\Database\Seeder;

class PrCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_categories')->delete();
        
        $prCategories = array(
        	array('pr_division_id'=>1,'name' => 'Above 500kV', 'code'=>1),
        	array('pr_division_id'=>1,'name' => '500kV', 'code'=>2),
          	array('pr_division_id'=>1,'name' => '220kV', 'code'=>3),
        	array('pr_division_id'=>1,'name' => '132kV', 'code'=>4),
        	array('pr_division_id'=>1,'name' => '11kV', 'code'=>5)
        );

        DB::table('pr_categories')->insert($prCategories);
    }
}
