<?php

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('clients')->delete();  
        $Clients = array(
        	
        	array('name' => 'National Transmission and Despatch Company (NTDC)'),
        	array('name' => 'Water and Power Development Authority (WAPDA)'),
        	array('name' => 'Government of Punjab'),
        	array('name' => 'Government of Sindh'),
        	array('name' => 'Government of KPK'),
        	array('name' => 'Lahore Electric Supply Company (LESCO)'),
        	array('name' => 'Gujranwala Electric Power Company (GEPCO)'),
        	array('name' => 'Faisalabad Electrical Supply Company (FESCO)'),
        	array('name' => 'Islamabad Electrical Supply Company (IESCO)'),
        	array('name' => 'Hyderabad Electrical Supply Company (HESCO)'),
        	array('name' => 'Sukkur Electrical Power Company (SEPCO)'),
        	array('name' => 'Quetta Electrical Supply Company (QESCO)'),
        	array('name' => 'Peshawar Electrical Supply Company (PESCO)'),
        	array('name' => 'Tribal Areas Electrical Supply Company (TESCO)'),
        	array('name' => 'Multan Electrical Power Company (MEPCO)'),
        	array('name' => 'K-Electric (KE)'),
        	array('name' => 'Pakhtunkhwa Energy Development Organization (PEDO)'),
        	array('name' => 'Irrigation Department, Punjab'),
        	array('name' => 'Irrigation Department, Sindh'),
            array('name' => 'BARQAAB Consulting Services (Pvt.) Ltd. (BARQAAB)'),

        );
        DB::table('clients')->insert($Clients);
    }
}
