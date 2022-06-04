<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(SubmissionTypeSeeder::class);
        $this->call(SubStatusSeeder::class);
        $this->call(PartnerSeeder::class);
    }
}
