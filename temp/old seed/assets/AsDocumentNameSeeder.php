<?php

use Illuminate\Database\Seeder;

class AsDocumentNameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('as_document_names')->delete();  
        $asDocumentNames = array(
        	array('name' => 'Purchase Invoice'),
        	array('name' => 'Purchase Approval'),
        	array('name' => 'Handing Over Slip'),
        	array('name' => 'image'),
        );
        DB::table('as_document_names')->insert($asDocumentNames);
    }
}
