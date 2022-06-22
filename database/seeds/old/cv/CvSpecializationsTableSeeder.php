<?php

use Illuminate\Database\Seeder;

class CvSpecializationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cv_specializations')->delete();
        $CvSpecializations = array(
            array('name' =>'Hydraulic Design'),
            array('name' => 'Contract Engineer'),
            array('name' => 'Construction Supervision Engineer'),
            array('name' => 'Geo-Technical Engineer'),
            array('name' => 'Hydrology & Sedimentation'),
            array('name' => 'Project Manager'),
            array('name' => 'Structural Design'),
            array('name' => 'Design Engineer'),
            array('name' => 'RE'),
            array('name' => 'CRE'),
        );
        DB::table('cv_specializations')->insert($CvSpecializations);
        
    }
}
