<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_educations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('education_id')->unsigned();
            $table->foreign('education_id')->references('id')->on('educations');

            $table->bigInteger('hr_employee_id')->unsigned();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');


            $table->bigInteger('country_id')->unsigned();
            $table->foreign('country_id')->references('id')->on('countries');
            $table->unique(['education_id', 'hr_employee_id'],'unique_education'); 
            $table->string('major')->nullable();
            $table->string('institute')->nullable();
            $table->string('from',15)->nullable();
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
        Schema::dropIfExists('hr_educations');
    }
}
