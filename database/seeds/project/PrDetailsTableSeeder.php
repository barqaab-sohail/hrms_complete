<?php

use Illuminate\Database\Seeder;

class PrDetailsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('pr_details')->delete();

        DB::table('pr_details')->insert([
          
            'name'=>'overhead',
            'contract_type_id' => 1,
            'client_id'=>20,
            'commencement_date'=>'2000-05-09',
            'actual_completion_date'=>'2090-05-07',
            'pr_status_id'=>1,
            'pr_role_id'=>1,
            'share'=>'100',
        ]);
    }
}
