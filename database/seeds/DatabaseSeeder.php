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
        $this->call(UserTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(EducationsTableSeeder::class);
        $this->call(GendersTableSeeder::class);
        $this->call(LanguageLevelsTableSeeder::class);
        $this->call(LanguageSkillsTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(MaritalStatusesTableSeeder::class);
        $this->call(MembershipsTableSeeder::class);
        $this->call(PrRolesTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(HrDesignationsTableSeeder::class);
        $this->call(HrStatusesTableSeeder::class);
    }
}
