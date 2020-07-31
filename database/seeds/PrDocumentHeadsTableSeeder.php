<?php

use Illuminate\Database\Seeder;

class PrDocumentHeadsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_document_heads')->delete();  
        $prDocumentHeads = array(
        	array('name' => 'General Correspondence'),
        	array('name' => 'Deployment of Staff'),
        	array('name' => 'Time Sheets'),
        	array('name' => 'Contracts'),
        	array('name' => 'RFP'),
           
        );
        DB::table('pr_document_heads')->insert($prDocumentHeads);
    }
}
