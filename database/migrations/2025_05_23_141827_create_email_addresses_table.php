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
        Schema::create('email_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('type')->default('company'); // company, project, personal, etc.
            $table->boolean('is_active')->default(true);
            $table->boolean('is_primary')->default(false);
            $table->text('description')->nullable();

            // Polymorphic relationship
            $table->unsignedBigInteger('emailable_id');
            $table->string('emailable_type');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_addresses');
    }
};
