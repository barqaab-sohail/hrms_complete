<?php

use Illuminate\Database\Seeder;

class PrWorkTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_work_types')->delete();
        
        $prWorkTypes = array(
        	array('pr_division_id'=>1,'name' => 'Grid Station', 'code'=>11),
        	array('pr_division_id'=>1,'name' => 'Transmission Line', 'code'=>12),
          	array('pr_division_id'=>1,'name' => 'Grid Station and Transmission Line', 'code'=>13),
        	array('pr_division_id'=>1,'name' => 'Survey', 'code'=>14),
        	array('pr_division_id'=>1,'name' => 'Survey and Soil Investigation', 'code'=>15),
        	array('pr_division_id'=>1,'name' => 'Underground Electrification', 'code'=>16),
        	array('pr_division_id'=>1,'name' => 'REP, SAP, Deposit Works', 'code'=>17),
        	array('pr_division_id'=>1,'name' => 'Validation', 'code'=>18),
        	array('pr_division_id'=>1,'name' => 'System Studies', 'code'=>19)
        );

        DB::table('pr_work_types')->insert($prWorkTypes);
    }
}
