<?php

use Illuminate\Database\Seeder;

class CvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        CvDisciplinesTableSeeder::class,
        CvSpecializationsTableSeeder::class,
        CvStagesTableSeeder::class,
        ]);
    }
}
