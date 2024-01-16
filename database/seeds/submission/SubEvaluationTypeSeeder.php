<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class SubEvaluationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \DB::table('sub_evaluation_types')->delete();  
        $subEvaluationTypes = array(
        	array('name' => 'Quality cum Cost Based'),
        	array('name' => 'Least Cost'),
        	array('name' => 'Quality Based'),
        );
         \DB::table('sub_evaluation_types')->insert($subEvaluationTypes);
    }
}
