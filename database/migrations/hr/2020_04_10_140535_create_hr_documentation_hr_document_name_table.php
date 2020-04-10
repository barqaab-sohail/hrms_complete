<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrDocumentationHrDocumentNameTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_documentation_hr_document_name', function (Blueprint $table) {
            
            $table->bigInteger('hr_documentation_id')->unsigned();
            $table->foreign('hr_documentation_id')->references('id')->on('hr_documentations')->onDelete('cascade');

            $table->bigInteger('hr_document_name_id')->unsigned();
            $table->foreign('hr_document_name_id')->references('id')->on('hr_document_names');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_documentation_hr_document_name');
    }
}
