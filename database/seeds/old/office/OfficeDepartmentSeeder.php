<?php

use Illuminate\Database\Seeder;

class OfficeDepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('office_departments')->delete();
        
        $officeDepartments = array(
        	array('office_id' => 1, 'name'=>'Chief Executive Officer'),
        	array('office_id' => 1, 'name'=>'General Manager (Power)'),
        	array('office_id' => 1, 'name'=>'General Manager (W&C)'),
        	array('office_id' => 1, 'name'=>'HR & Administration')
        );

        DB::table('office_departments')->insert($officeDepartments);
    }
}
