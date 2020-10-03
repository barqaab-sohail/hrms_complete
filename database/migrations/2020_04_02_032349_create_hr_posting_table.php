<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrPostingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_postings', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('hr_documentation_id')->unsigned();
            $table->date('effective_date');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');
            $table->foreign('hr_documentation_id')->references('id')->on('hr_documentations');
           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_postings');
    }
}
