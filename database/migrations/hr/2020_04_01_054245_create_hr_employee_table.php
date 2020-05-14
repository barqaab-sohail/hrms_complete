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
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('father_name');
            $table->string('cnic',20)->unique();
            $table->date('cnic_expiry');
            $table->date('date_of_birth');
            $table->string('employee_no',20)->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable() ;
            $table->bigInteger('gender_id')->unsigned();
            $table->bigInteger('hr_status_id')->unsigned()->default(1)->comment('1 Onboard, 2 Resigned, 3 Terminated, 4 Retired' );
             $table->bigInteger('marital_status_id')->unsigned();
            $table->bigInteger('religion_id')->unsigned();
            $table->bigInteger('domicile_id')->unsigned()->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('gender_id')->references('id')->on('genders');
            $table->foreign('marital_status_id')->references('id')->on('marital_statuses');
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
