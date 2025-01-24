<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_contacts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('hr_contact_type_id')->unsigned();
            $table->string('house',50)->nullable();
            $table->string('street',190)->nullable();
            $table->string('town',190);
            $table->string('tehsil',190)->nullable();
            $table->bigInteger('city_id')->unsigned();
            $table->bigInteger('state_id')->unsigned();
            $table->bigInteger('country_id')->unsigned();
            
            $table->timestamps();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->foreign('hr_contact_type_id')->references('id')->on('hr_contact_types');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_contacts');
    }
}
