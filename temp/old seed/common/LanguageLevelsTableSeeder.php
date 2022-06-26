<?php

use Illuminate\Database\Seeder;

class LanguageLevelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('language_levels')->delete();
        
        $LanguageLevels = array(
        	array('name' => 'Excellent'),
        	array('name' => 'Good'),
        	array('name' => 'Average'),
        );

         DB::table('language_levels')->insert($LanguageLevels);
    }
}
