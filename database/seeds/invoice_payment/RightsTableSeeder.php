<?php

use Illuminate\Database\Seeder;

class RightsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rights')->delete();  
        $rights = array(
        	array('name' => 'No Access'),
            array('name' => 'View Record'),
        	array('name' => 'Edit Record'),
        	array('name' => 'Delete Record'),
        );
        DB::table('rights')->insert($rights);
    }
}
