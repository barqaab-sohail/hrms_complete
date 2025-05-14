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
        Schema::create('securities', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['bid_security', 'performance_guarantee']);
            $table->enum('bid_security_type', ['pay_order_cdr', 'bank_guarantee'])->nullable();
            $table->string('favor_of');
            $table->date('date_issued');
            $table->date('expiry_date');
            $table->decimal('amount', 15, 2);
            $table->string('project_name');
            $table->string('document_path')->nullable();
            $table->text('remarks')->nullable();
            $table->string('reference_number')->nullable();
            $table->enum('status', ['active', 'expired', 'released'])->default('active');
            $table->foreignId('client_id')->nullable()->constrained('clients');
            $table->foreignId('submitted_by')->nullable()->constrained('partners');
            $table->foreignId('bank_id')->nullable()->constrained('banks');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('securities');
    }
};
