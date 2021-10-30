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
        	array('name' => 'Remuneration (Salary Cost)'),
            array('name' => 'Reimbursable (Direct Cost)'),
            array('name' => 'Escalations'),
            array('name' => 'Both Remuneration & Reimbursable')
        );
        DB::table('invoice_types')->insert($invoiceTypes);
    }
}
