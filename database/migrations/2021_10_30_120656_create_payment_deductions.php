<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentDeductions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_deductions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('payment_receive_id')->unsigned();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->decimal('withholding_tax',12,0);
            $table->decimal('sales_tax',12,0);
            $table->decimal('others',12,0)->nullable();
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('payment_receive_id')->references('id')->on('payment_receives')->onDelete('cascade');
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
        Schema::dropIfExists('payment_deductions');
    }
}
