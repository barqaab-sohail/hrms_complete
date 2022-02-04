<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeSanctioneds extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('le_sanctioneds', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('manager_id')->unsigned();
            $table->bigInteger('leave_id')->unsigned();
            $table->bigInteger('le_status_type_id')->unsigned();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('manager_id')->references('id')->on('hr_employees');
            $table->foreign('leave_id')->references('id')->on('leaves')->onDelete('cascade');
             $table->foreign('le_status_type_id')->references('id')->on('le_status_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('le_sanctioneds');
    }
}
