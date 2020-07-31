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
        $this->call(PrDivisionsTableSeeder::class);
        $this->call(PrCategoriesTableSeeder::class);
        $this->call(ProjectPositionTypeSeeder::class);
       //$this->call(PrRolesSeeder::class);
        //$this->call(PrStatusesTableSeeder::class);
        $this->call(PrWorkTypesSeeder::class);
        $this->call(PrDocumentHeadsTableSeeder::class);
        //$this->call(PrDetailsTableSeeder::class);
        
    }
}
