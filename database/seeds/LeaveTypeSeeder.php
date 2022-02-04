<?php

use Illuminate\Database\Seeder;

class LeaveTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('le_types')->delete();
        
        $letypes = array(
        	array('name' => 'Casual Leave', 'annual_total'=>12,'accumulative_limit'=>0,'from_date'=>'january','to_date'=>'december'),
        	array('name' => 'Earned Leave', 'annual_total'=>18,'accumulative_limit'=>60,'from_date'=>'january','to_date'=>'december'),
            array('name' => 'Without Pay Leave', 'annual_total'=>365,'accumulative_limit'=>0,'from_date'=>'january','to_date'=>'december'),
            array('name' => 'Compensatory Leave', 'annual_total'=>0,'accumulative_limit'=>0,'from_date'=>'january','to_date'=>'december'),
        );

        DB::table('le_types')->insert($letypes);
    }
}
