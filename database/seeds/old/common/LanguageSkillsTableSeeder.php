<?php

use Illuminate\Database\Seeder;

class LanguageSkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('language_skills')->delete();
        
        $LanguageSkills = array(
        	array('name' => 'Reading'),
        	array('name' => 'Speaking'),
        	array('name' => 'Writing'),
        	array('name' => 'Listening'),
        );

        DB::table('language_skills')->insert($LanguageSkills);
    }
}
