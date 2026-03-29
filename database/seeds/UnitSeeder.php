<?php

//namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('units')->delete();
        $units = array(
            array('name' => 'Litre'),
            array('name' => 'Numbers'),
            array('name' => 'Kilograms'),
            array('name' => 'Gram'),
            array('name' => 'Meter'),
            array('name' => 'Foot'),
        );
        DB::table('units')->insert($units);
    }
}
