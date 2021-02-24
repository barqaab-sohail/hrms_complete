<?php

use Illuminate\Database\Seeder;

class InvoiceTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invoice_types')->delete();  
        $invoiceTypes = array(
        	array('name' => 'Monthly'),
        	array('name' => 'Percentage Wise'),
        	array('name' => 'Activities Wise'),
        	array('name' => 'Other'),
        );
        DB::table('invoice_types')->insert($invoiceTypes);
    }
}
