<?php

use Illuminate\Database\Seeder;

class LeaveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
	        LeaveStatusTypeSeeder::class,
	        LeaveTypeSeeder::class,
        ]);
    }
}
