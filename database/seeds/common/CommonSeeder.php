<?php

use Illuminate\Database\Seeder;

class CommonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
	        BanksTableSeeder::class,
	        BloodGroupsTableSeeder::class,
	        CitiesTableSeeder::class,
	        ClientsTableSeeder::class,
	        ContractTypesTableSeeder::class,
	        CountriesTableSeeder::class,
	        EducationsTableSeeder::class,
	        GendersTableSeeder::class,
	        LanguageLevelsTableSeeder::class,
	        LanguageSkillsTableSeeder::class,
	        LanguagesTableSeeder::class,
	        MaritalStatusesTableSeeder::class,
	        MembershipsTableSeeder::class,
	        ReligionsTableSeeder::class,
	        StatesTableSeeder::class,
	        CurrencySeeder::class,
	        PartnerSeeder::class,
	        OfficeSeeder::class,
        ]);
    }
}
