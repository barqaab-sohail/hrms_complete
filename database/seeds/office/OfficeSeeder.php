<?php

use Illuminate\Database\Seeder;

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('offices')->delete();
        
        $Offices = array(
        	array('name' => 'Head Office'),
        	array('name' => 'Finance Office'),
        	array('name' => 'Design Office'),
        	array('name' => 'CPM Office'),
        	array('name' => 'Site Office'),
        );

         DB::table('offices')->insert($Offices);
    }
}
