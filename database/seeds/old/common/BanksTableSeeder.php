<?php

use Illuminate\Database\Seeder;

class BanksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('banks')->delete();  
        $Banks = array(
        	array('name' => 'Al Baraka Bank (Pakistan) Limited'),
        	array('name' => 'Allied Bank Limited'),
        	array('name' => 'Askari Bank Limited'),
        	array('name' => 'Bank Alfalah Limited'),
        	array('name' => 'Bank Al-Habib Limited'),
        	array('name' => 'Bank Islami Pakistan Limited'),
        	array('name' => 'Burj Bank Limited'),
        	array('name' => 'Citi Bank N.A.'),
        	array('name' => 'Deutsche Bank A.G.'),
        	array('name' => 'Dubai Islamic Bank Pakistan Limited'),
        	array('name' => 'Faysal Bank Limited'),
        	array('name' => 'First Women Bank Limited'),
        	array('name' => 'Habib Bank Limited'),
        	array('name' => 'Habib Metropolitan Bank Limited'),
        	array('name' => 'Industrial and Commercial Bank of China'),
        	array('name' => 'Industrial Development Bank of Pakistan'),
        	array('name' => 'JS Bank Limited'),
        	array('name' => 'MCB Bank Limited'),
        	array('name' => 'MCB Islamic Bank Limited'),
        	array('name' => 'Meezan Bank Limited'),
        	array('name' => 'National Bank of Pakistan'),
        	array('name' => 'NIB Bank Limited'),
        	array('name' => 'S.M.E. Bank Limited'),
        	array('name' => 'Samba Bank Limited'),
        	array('name' => 'Silk Bank Limited'),
        	array('name' => 'Sindh Bank Limited'),
        	array('name' => 'Soneri Bank Limited'),
        	array('name' => 'Standard Chartered Bank (Pakistan) Limited'),
        	array('name' => 'Summit Bank Limited'),
        	array('name' => 'The Bank of Khyber'),
        	array('name' => 'The Bank of Punjab'),
        	array('name' => 'The Bank of Tokyo-Mitsubishi Limited'),
        	array('name' => 'The Punjab Provincial Cooperative Bank Limited'),
        	array('name' => 'United Bank Limited'),
        	array('name' => 'Zarai Taraqiati Bank Limited'),
        );
        DB::table('banks')->insert($Banks);
    }
}
