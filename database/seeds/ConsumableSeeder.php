<?php

namespace Database\Seeders;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class ConsumableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('consumables')->delete();  
        $consumables = array(
        	array('name' => 'Petrol'),
        	array('name' => 'Diesel'),
            array('name' => 'M-Tag'),
            array('name' => 'Toll Tax'),
        );
        DB::table('consumables')->insert($consumables);
    }
}
