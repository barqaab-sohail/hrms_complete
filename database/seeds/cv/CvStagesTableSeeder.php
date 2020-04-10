<?php

use Illuminate\Database\Seeder;

class CvStagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('cv_stages')->delete();
        $CvStages = array(
            array('name' =>'Planning'),
            array('name' => 'Design'),
            array('name' => 'Construction'),
            array('name' => 'O & M'),
        );
        DB::table('cv_stages')->insert($CvStages);
    }
}
