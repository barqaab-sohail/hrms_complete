<?php

use Illuminate\Database\Seeder;

class PrFolderNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_folder_names')->delete();  
        $prFolderNames = array(
        	array('name' => 'General Correspondence'),
        	array('name' => 'Deployment of Staff'),
        	array('name' => 'Time Sheets'),
        	array('name' => 'Contracts'),
        	array('name' => 'RFP'),
           
        );
        DB::table('pr_folder_names')->insert($prFolderNames);
    }
}
