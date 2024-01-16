<?php

use Illuminate\Database\Seeder;

class MembershipsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('memberships')->delete();
        
        $Memberships = array(
        	array('name' => 'Pakistan Engineering Council'),
        	
        );

         DB::table('memberships')->insert($Memberships);
    }
}
