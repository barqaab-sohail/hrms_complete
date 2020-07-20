<?php

use Illuminate\Database\Seeder;

class PrDocumentNamesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_document_names')->delete();  
        $PrDocumentNames = array(
        	array('name' => 'Contract Agreement'),
        	array('name' => 'Letter of Commencement'),
        	array('name' => 'Completion Certificate'),
        	array('name' => 'JV Agreement'),
        	array('name' => 'Others'),
           
        );
        DB::table('pr_document_names')->insert($PrDocumentNames);
    }
}
