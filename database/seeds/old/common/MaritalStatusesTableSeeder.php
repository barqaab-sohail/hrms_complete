<?php

use Illuminate\Database\Seeder;

class MaritalStatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('marital_statuses')->delete();
        
        $MaritalStatuses = array(
        	array('name' => 'Single'),
        	array('name' => 'Married'),
        	array('name' => 'Separated'),
        	array('name' => 'Widowed'),
        );

         DB::table('marital_statuses')->insert($MaritalStatuses);
    }
}
