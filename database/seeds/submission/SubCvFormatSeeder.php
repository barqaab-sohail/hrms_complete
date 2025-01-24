<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;

class SubCvFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         \DB::table('sub_cv_formats')->delete();  
        $SubCvFormats = array(
        	array('name' => 'PEC'),
        	array('name' => 'Tech-6'),
        	array('name' => 'World Bank'),
            array('name' => 'Other'),
        );
         \DB::table('sub_cv_formats')->insert($SubCvFormats);
    }
}
