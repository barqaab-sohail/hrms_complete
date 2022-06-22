<?php

use Illuminate\Database\Seeder;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaymentStatusTableSeeder::class);
        $this->call(InvoiceTypeTableSeeder::class);
        $this->call(RightsTableSeeder::class);
    }
}
