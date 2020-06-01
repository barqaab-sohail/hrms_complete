<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrDocumentationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_documentations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->string('description');
            $table->date('document_date')->nullable();
            $table->string('file_name');
            $table->string('extension');
            $table->string('path');
            $table->string('size',20);
            $table->text('content')->nullable();
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
        Schema::dropIfExists('hr_documentations');
    }
}
