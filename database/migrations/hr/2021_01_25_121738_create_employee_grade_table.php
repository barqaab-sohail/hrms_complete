<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_grades', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('hr_grade_id')->unsigned();
            $table->date('effective_date');
            $table->timestamps();        
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->foreign('hr_grade_id')->references('id')->on('hr_grades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_grades');
    }
}
