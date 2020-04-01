<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrEmployeeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name');
            $table->string('cnic',20);
            $table->date('cnic_expiry');
            $table->date('date_of_birth');
            $table->string('employee_no',20);
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('gender_id')->unsigned();
            $table->bigInteger('hr_status_id')->unsigned();
            $table->bigInteger('religion_id')->unsigned();
            $table->bigInteger('domicile_id')->unsigned();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->foreign('hr_status_id')->references('id')->on('hr_statuses');
            $table->foreign('religion_id')->references('id')->on('religions');
            $table->foreign('domicile_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_employees');
    }
}
