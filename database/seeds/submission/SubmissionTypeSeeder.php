<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class SubmissionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \DB::table('sub_types')->delete();  
        $subTypes = array(
        	array('name' => 'EOI'),
        	array('name' => 'PQD'),
        	array('name' => 'RFP'),
        );
         \DB::table('sub_types')->insert($subTypes);
    }
}
