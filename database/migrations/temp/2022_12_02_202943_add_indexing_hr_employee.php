<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexingHrEmployee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->index(['first_name', 'last_name', 'father_name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('hr_employees', function (Blueprint $table) {
            $table->dropIndex('hr_employees_first_name_last_name_father_name_index');
        });
    }
}
