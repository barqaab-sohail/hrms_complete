<?php

use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
	       PrDivisionsTableSeeder::class,
	       PrFolderNamesTableSeeder::class,
	       PrRolesTableSeeder::class,
	       PrStatusesTableSeeder::class,
	       PrWorkTypesSeeder::class,
        ]);


    }
}
