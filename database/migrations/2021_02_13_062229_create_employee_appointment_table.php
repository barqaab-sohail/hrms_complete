<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_appointments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('hr_employee_id')->unsigned()->unique();
            $table->bigInteger('employee_project_id')->unsigned();
            $table->bigInteger('hr_letter_type_id')->unsigned();
            $table->bigInteger('employee_manager_id')->unsigned();
            $table->bigInteger('employee_designation_id')->unsigned();
            $table->bigInteger('employee_department_id')->unsigned();
            $table->bigInteger('employee_salary_id')->unsigned();
            $table->bigInteger('employee_office_id')->nullable()->unsigned();
            $table->string('reference_no')->nullable();
            $table->date('joining_date');
            $table->date('expiry_date')->nullable();
            $table->bigInteger('employee_grade_id')->nullable()->unsigned();
            $table->bigInteger('employee_category_id')->unsigned();
            $table->bigInteger('hr_employee_type_id')->unsigned();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->foreign('employee_project_id')->references('id')->on('pr_details');
            $table->foreign('hr_letter_type_id')->references('id')->on('hr_letter_types');
            $table->foreign('employee_manager_id')->references('id')->on('hr_employees');
            $table->foreign('employee_designation_id')->references('id')->on('hr_designations');
            $table->foreign('employee_department_id')->references('id')->on('hr_departments');
            $table->foreign('employee_salary_id')->references('id')->on('hr_salaries');
            $table->foreign('employee_office_id')->references('id')->on('offices');
            $table->foreign('employee_grade_id')->references('id')->on('hr_grades');
            $table->foreign('employee_category_id')->references('id')->on('hr_categories');
            $table->foreign('hr_employee_type_id')->references('id')->on('hr_employee_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_appointments');
    }
}
