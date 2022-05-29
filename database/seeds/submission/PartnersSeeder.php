<?php

use Illuminate\Database\Seeders;
use DB;

class PartnersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('partners')->delete();  
        $partners = array(
        	array('name' => 'National Engineering Services Pakistan Pvt. Ltd. (NESPAK)'),
        	array('name' => 'National Development Consultants Pvt. Ltd. (NDC)'),
        	array('name' => 'Associated Consulting Engineers Pvt. Ltd. (ACE)'),
        	array('name' => 'HATCH Canada'),
        	array('name' => 'OMS (Private) Limited'),
        	array('name' => 'Tractebel Engineering GmbH'),
           
        );
        DB::table('partners')->insert($partners);
    }
}
