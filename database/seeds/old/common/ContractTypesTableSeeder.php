<?php

use Illuminate\Database\Seeder;

class ContractTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('contract_types')->delete();  
        $ContractTypes = array(
        	array('name' => 'Lumpsum'),
        	array('name' => 'Man-Months'),
        );
        DB::table('contract_types')->insert($ContractTypes);
    }
}
