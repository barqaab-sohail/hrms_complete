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
        $this->call(BanksTableSeeder::class);
        $this->call(BloodGroupsTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(ContractTypesTableSeeder::class);
        $this->call(CountriesTableSeeder::class);
        $this->call(EducationsTableSeeder::class);
        $this->call(GendersTableSeeder::class);
        $this->call(LanguageLevelsTableSeeder::class);
        $this->call(LanguageSkillsTableSeeder::class);
        $this->call(LanguagesTableSeeder::class);
        $this->call(MaritalStatusesTableSeeder::class);
        $this->call(MembershipsTableSeeder::class);
        $this->call(ReligionsTableSeeder::class);
        $this->call(StatesTableSeeder::class);
        $this->call(HrContactTypesTableSeeder::class);
        $this->call(HrDepartmentsTableSeeder::class);
        $this->call(HrDesignationsTableSeeder::class);
        $this->call(HrLetterTypesTableSeeder::class);
        $this->call(HrStatusesTableSeeder::class);
        $this->call(PrRolesTableSeeder::class);
        $this->call(PrStatusesTableSeeder::class);
        $this->call(PrDetailsTableSeeder::class);
    }
}
