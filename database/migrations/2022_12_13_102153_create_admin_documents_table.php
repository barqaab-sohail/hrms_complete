<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('reference_no')->nullable();
            $table->string('description');
            $table->date('document_date');
            $table->string('file_name');
            $table->string('extension');
            $table->string('path');
            $table->string('size', 20);
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
        Schema::dropIfExists('admin_documents');
    }
}
