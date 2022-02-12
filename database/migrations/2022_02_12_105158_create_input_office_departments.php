<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInputOfficeDepartments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('input_office_departments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('input_id')->unsigned();
            $table->bigInteger('office_department_id')->unsigned();
            $table->timestamps();
            $table->foreign('input_id')->references('id')->on('inputs')->onDelete('cascade');
            $table->foreign('office_department_id')->references('id')->on('office_departments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('input_office_departments');
    }
}
