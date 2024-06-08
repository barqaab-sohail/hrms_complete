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
        Schema::create('as_consumables', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('asset_id')->unsigned();
            $table->bigInteger('consumable_id')->unsigned();
            $table->integer('consumable_cost');
            $table->date('consumable_date');
            $table->timestamps();
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->foreign('consumable_id')->references('id')->on('consumables')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('as_consumables');
    }
};
