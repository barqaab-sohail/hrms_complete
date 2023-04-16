<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubNominatePersonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_nominate_persons', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('sub_position_id')->unsigned();
            $table->bigInteger('hr_employee_id')->unsigned()->nullable();
            $table->string('name');
            $table->timestamps();
            $table->foreign('sub_position_id')->references('id')->on('sub_positions')->onDelete('cascade');
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_nominate_persons');
    }
}
