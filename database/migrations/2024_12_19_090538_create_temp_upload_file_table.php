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
        Schema::create('temp_upload_file', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('file_name');
            $table->string('extension');
            $table->string('path');
            $table->string('size',20); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_upload_file');
    }
};
