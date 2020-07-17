<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_contacts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->string('name');
            $table->string('designation')->nullable();
            $table->string('address')->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('ss_contacts');
    }
}
