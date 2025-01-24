<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrEmployeeLanguageTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_employee_language', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('language')->unsigned();
            $table->foreign('language')->references('id')->on('languages');

            $table->bigInteger('hr_employee_id')->unsigned();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');

            $table->bigInteger('language_level_id')->unsigned();
            $table->foreign('language_level_id')->references('id')->on('language_levels');

            $table->bigInteger('language_skill_id')->unsigned();
            $table->foreign('language_skill_id')->references('id')->on('language_skills');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_employee_language');
    }
}
