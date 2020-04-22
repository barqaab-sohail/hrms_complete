<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationHrEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education_hr_employee', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigInteger('education_id')->unsigned();
            $table->foreign('education_id')->references('id')->on('educations');

            $table->bigInteger('hr_employee_id')->unsigned();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');


            $table->bigInteger('country')->unsigned();
            $table->foreign('country')->references('id')->on('countries');

            $table->string('institute')->nullable();
            $table->string('from',15);
            $table->string('to',15);
            $table->float('total_marks')->nullable(); //4+2 i.e 9999.99
            $table->float('marks_obtain')->nullable();
            $table->string('grade',20)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('education_hr_employee');
    }
}
