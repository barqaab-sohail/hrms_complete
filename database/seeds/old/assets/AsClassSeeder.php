<?php

use Illuminate\Database\Seeder;

class AsClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('as_classes')->delete();  
        $asClasses = array(
        	array('name' => 'Vehicle'),
        	array('name' => 'Electric Item'),
        	array('name' => 'Furniture'),
        	array('name' => 'Crockery'),
        );
        DB::table('as_classes')->insert($asClasses);
    }
}
