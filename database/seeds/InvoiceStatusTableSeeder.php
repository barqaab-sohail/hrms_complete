<?php

use Illuminate\Database\Seeder;

class InvoiceStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('invoice_statuses')->delete();  
        $invoiceStatuses = array(
        	array('name' => 'Pending'),
        	array('name' => 'Received'),
        );
        DB::table('invoice_statuses')->insert($invoiceStatuses);
    }
}
