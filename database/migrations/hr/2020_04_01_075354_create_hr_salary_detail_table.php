<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrSalaryDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_salary_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('hr_salary_id')->unsigned();
            $table->bigInteger('hr_common_model_id')->unsigned();
            $table->string('category',5);
            $table->tinyInteger('grade')->nullable();
           
            $table->timestamps();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->foreign('hr_salary_id')->references('id')->on('hr_salaries');
            $table->foreign('hr_common_model_id')->references('id')->on('hr_common_models');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_salary_details');
    }
}
