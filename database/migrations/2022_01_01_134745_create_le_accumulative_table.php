<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeAccumulativeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('le_accumulatives', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('le_type_id')->unsigned();
            $table->integer('accumulative_total');
            $table->date('date');
            $table->timestamps();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees');
            $table->foreign('le_type_id')->references('id')->on('le_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('le_accumulatives');
    }
}
