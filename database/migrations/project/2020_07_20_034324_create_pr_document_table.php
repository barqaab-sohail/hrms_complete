<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrDocumentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_folder_name_id')->unsigned();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->string('reference_no')->nullable();
            $table->string('description');
            $table->date('document_date');
            $table->string('file_name');
            $table->string('extension');
            $table->string('path');
            $table->string('size',20);
            $table->timestamps();
            $table->foreign('pr_folder_name_id')->references('id')->on('pr_folder_names');
            $table->foreign('pr_detail_id')->references('id')->on('pr_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_documents');
    }
}
