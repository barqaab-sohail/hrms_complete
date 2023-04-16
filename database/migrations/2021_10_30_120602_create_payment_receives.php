<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentReceives extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_receives', function (Blueprint $table) {
             $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('invoice_id')->unsigned();
            $table->bigInteger('pr_detail_id')->unsigned();
             $table->bigInteger('payment_status_id')->unsigned();
            $table->decimal('amount',12,0);
            $table->date('payment_date');
            $table->string('cheque_no')->nullable();
            $table->date('cheque_date')->nullable();
            $table->timestamps();
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
             $table->foreign('pr_detail_id')->references('id')->on('pr_details')->onDelete('cascade');
             $table->foreign('payment_status_id')->references('id')->on('payment_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_receives');
    }
}
