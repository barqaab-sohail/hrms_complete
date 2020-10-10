<?php

use Illuminate\Database\Seeder;

class SubTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sub_types')->delete();  
        $subTypes = array(
        	array('name' => 'EOI'),
        	array('name' => 'RFP'),
        	array('name' => 'PQD'),
        );
        DB::table('sub_types')->insert($subTypes);
    }
}
