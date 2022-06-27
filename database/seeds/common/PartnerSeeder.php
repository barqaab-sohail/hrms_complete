<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('partners')->delete();  
        $partners = array(
            array('name' => 'BARQAAB Consulting Services (Pvt.) Ltd. (BARQAAB)'),
        	array('name' => 'National Engineering Services Pakistan Pvt. Ltd. (NESPAK)'),
        	array('name' => 'National Development Consultants Pvt. Ltd. (NDC)'),
        	array('name' => 'Associated Consulting Engineers Pvt. Ltd. (ACE)'),
        	array('name' => 'HATCH Canada'),
        	array('name' => 'OMS (Private) Limited'),
        	array('name' => 'Tractebel Engineering GmbH'),
           
        );
        \DB::table('partners')->insert($partners);
    }
}
