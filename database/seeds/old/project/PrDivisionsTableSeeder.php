<?php

use Illuminate\Database\Seeder;

class PrDivisionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_divisions')->delete();
        
        $prDivisions = array(
        	array('name' => 'Water', 'code'=>1),
        	array('name' => 'Power', 'code'=>2),
        );

        DB::table('pr_divisions')->insert($prDivisions);
    }
}
