<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class SubFinancialTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \DB::table('sub_financial_types')->delete();  
        $subFinancialTypes = array(
        	array('name' => 'Man Month'),
        	array('name' => 'Lumpsum'),
        	array('name' => 'activities'),
        );
         \DB::table('sub_financial_types')->insert($subFinancialTypes);
    }
}
