<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrPromotionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_promotions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('hr_manager_id')->unsigned();
            $table->bigInteger('hr_designation_id')->unsigned();
            $table->bigInteger('hr_department_id')->unsigned();
            $table->bigInteger('hr_salary_id')->unsigned();
            $table->bigInteger('hr_documentation_id')->unsigned();

            $table->date('effective_date');
            $table->tinyInteger('grade')->nullable();
            $table->string('category',1);
            $table->string('remarks');
            $table->timestamps();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->foreign('hr_manager_id')->references('id')->on('hr_employees');
            $table->foreign('hr_designation_id')->references('id')->on('hr_designations');
            $table->foreign('hr_department_id')->references('id')->on('hr_departments');
            $table->foreign('hr_salary_id')->references('id')->on('hr_salaries');
            $table->foreign('hr_documentation_id')->references('id')->on('hr_documentations');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_promotions');
    }
}
