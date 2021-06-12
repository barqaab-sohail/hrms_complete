<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsAllocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_allocations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('asset_id')->unsigned();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->date('date');
            $table->timestamps();
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('as_allocations');
    }
}
