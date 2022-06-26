<?php

use Illuminate\Database\Seeder;

class CvDisciplinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cv_disciplines')->delete();
        $CvDisciplines = array(
            array('name' => 'Dam'),
            array('name' => 'Hydropower & Dam'),
            array('name' => 'Irrigation & Drainage'),
            array('name' => 'Canal & Head Works'),
            array('name' => 'Infrastructure (Roads & Buildings)'),
            array('name' => 'Grid Station & T/L'),
            array('name' => 'Power Distribution System'),
        );
        DB::table('cv_disciplines')->insert($CvDisciplines);
    }
}
