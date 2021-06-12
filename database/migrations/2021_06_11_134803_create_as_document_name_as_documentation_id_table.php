<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAsDocumentNameAsDocumentationIdTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('as_document_name_as_documentation_id', function (Blueprint $table) {
                 $table->engine = 'InnoDB';
            $table->bigInteger('as_documentation_id')->unsigned();
            $table->foreign('as_documentation_id')->references('id')->on('as_documentations')->onDelete('cascade');
            $table->bigInteger('asset_id')->unsigned();

            $table->bigInteger('as_document_name_id')->unsigned();
            $table->foreign('as_document_name_id')->references('id')->on('as_document_names');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
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
        Schema::dropIfExists('as_document_name_as_documentation_id');
    }
}
