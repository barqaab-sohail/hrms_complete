<?php

use Illuminate\Database\Seeder;

class HrSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
	        HrCategoriesTableSeeder::class,
	        HrContactTypesTableSeeder::class,
	        HrDepartmentsTableSeeder::class,
	        HrDesignationsTableSeeder::class,
	        HrDocumentNamesTableSeeder::class,
	        HrEmployeeTypesTableSeeder::class,
	        HrGradesTableSeeder::class,
	        HrLetterTypesTableSeeder::class,
	        HrStatusesTableSeeder::class,
        ]);
    }
}
