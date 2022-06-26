<?php

use Illuminate\Database\Seeder;

class PrStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_statuses')->delete();
        
        $PrStatuses = array(
        	array('name' => 'In Progress'),
        	array('name' => 'Completed'),
        	array('name' => 'Suspended'),
        );

         DB::table('pr_statuses')->insert($PrStatuses);
    }
}
