<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('photocopy_documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('photocopy_id')->unsigned();
            $table->string('reference_no')->nullable();
            $table->string('description');
            $table->date('document_date');
            $table->string('file_name');
            $table->string('extension');
            $table->string('path');
            $table->string('size', 20);
            $table->timestamps();
            $table->foreign('photocopy_id')->references('id')->on('photocopies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photocopy_documents');
    }
};
