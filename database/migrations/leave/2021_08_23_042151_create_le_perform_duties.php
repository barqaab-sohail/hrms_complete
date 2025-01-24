<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLePerformDuties extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('le_perform_duties', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('leave_id')->unsigned();
            $table->timestamps();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees');
             $table->foreign('leave_id')->references('id')->on('leaves')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('le_perform_duties');
    }
}
