<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrAppointmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_appointments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->bigInteger('hr_letter_type_id')->unsigned();
            $table->bigInteger('hr_manager_id')->unsigned();
            $table->bigInteger('hr_designation_id')->unsigned();
            $table->bigInteger('hr_department_id')->unsigned();
            $table->bigInteger('hr_salary_id')->unsigned();
            $table->bigInteger('office_id')->nullable()->unsigned();
            $table->string('reference_no')->nullable();
            $table->date('joining_date');
            $table->date('expiry_date')->nullable();
            $table->tinyInteger('grade')->nullable();
            $table->string('category',1);
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->foreign('pr_detail_id')->references('id')->on('pr_details');
            $table->foreign('hr_letter_type_id')->references('id')->on('hr_letter_types');
            $table->foreign('hr_manager_id')->references('id')->on('hr_employees');
            $table->foreign('hr_designation_id')->references('id')->on('hr_designations');
            $table->foreign('hr_department_id')->references('id')->on('hr_departments');
            $table->foreign('hr_salary_id')->references('id')->on('hr_salaries');
            $table->foreign('office_id')->references('id')->on('offices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_appointments');
    }
}
