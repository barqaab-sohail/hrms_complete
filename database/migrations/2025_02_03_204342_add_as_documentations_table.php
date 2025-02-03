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
        Schema::table('as_documentations', function (Blueprint $table) {
            $table->string('reference_no')->after('description')->nullable();
            $table->date('document_date')->after('reference_no')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('as_documentations', function (Blueprint $table) {
            $table->dropColumn('reference_no');
            $table->dropColumn('document_date');
        });
    }
};
