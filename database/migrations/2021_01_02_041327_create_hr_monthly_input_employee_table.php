<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrMonthlyInputEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_monthly_input_employees', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('hr_monthly_input_project_id')->unsigned();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('hr_designation_id')->unsigned();
            $table->float('input');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('hr_monthly_input_project_id')->references('id')->on('hr_monthly_input_projects')->onDelete('cascade');
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees');
            $table->foreign('hr_designation_id')->references('id')->on('hr_designations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_monthly_input_employees');
    }
}
