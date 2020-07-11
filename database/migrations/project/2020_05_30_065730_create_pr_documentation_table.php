<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrDocumentationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_documentations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->date('document_date');
            $table->string('description');
            $table->string('file_name');
            $table->string('extension');
            $table->string('path');
            $table->string('size',20);
            $table->mediumText('content')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('pr_documentations');
    }
}
