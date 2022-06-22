<?php

use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AssetSeeder::class);
        $this->call(CommonSeeder::class);
        $this->call(CvSeeder::class);
        $this->call(HrSeeder::class);
        $this->call(InvoiceSeeder::class);
        $this->call(LeaveSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(LoginSeeder::class);
    }
}
