<?php

use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AsClassSeeder::class);
        $this->call(AsSubClassSeeder::class);
        $this->call(AsConditionTypeSeeder::class);
        $this->call(AsDocumentNameSeeder::class);
        $this->call(AsPurchaseConditionSeeder::class);
    }
}
