<?php

use Illuminate\Database\Seeder;

class PaymentStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('payment_statuses')->delete();  
        $paymentStatuses = array(
        	array('name' => 'Partially Received'),
        	array('name' => 'Received')
        );
        DB::table('payment_statuses')->insert($paymentStatuses);
    }
}
