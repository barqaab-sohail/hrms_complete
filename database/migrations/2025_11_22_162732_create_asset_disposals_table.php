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
        Schema::create('asset_disposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('asset_id')->constrained()->onDelete('cascade');
            $table->date('sold_date');
            $table->decimal('sold_price', 10, 2)->nullable();
            $table->text('reason')->nullable();
            $table->string('sold_to')->nullable(); // Who it was sold to
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('asset_id');
            $table->index('sold_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_disposals');
    }
};
