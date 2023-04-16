<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('hr_employees')->delete();

        \DB::table('hr_employees')->insert([
            'first_name' => "Syed Azhar",
            'last_name' => "Ali Shah",
            'father_name' => "Syed Safdar Hussain Shah",
            'date_of_birth' => "1979-06-08",
            'employee_no' => "1000173",
            'user_id' => 1,
            'gender_id' => 1,
            'hr_status_id' => 1,
            'marital_status_id' => 2,
            'religion_id' => 1,
            'hr_status_id' => 1,
            'hr_status_id' => 1,
            'cnic' => '35101-4549497-3',
            'cnic_expiry' => "2028-05-23",
        ]);
    }
}
