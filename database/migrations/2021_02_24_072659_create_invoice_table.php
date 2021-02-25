<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->bigInteger('invoice_type_id')->unsigned();
            $table->bigInteger('invoice_status_id')->unsigned()->default(1)->comment('1 Pending, 2 Received' );
            $table->string('invoice_no')->unique();
            $table->date('invoice_date');
            $table->text('description');
            $table->text('reference');
            $table->timestamps();
            $table->foreign('pr_detail_id')->references('id')->on('pr_details')->onDelete('cascade');
            $table->foreign('invoice_type_id')->references('id')->on('invoice_types');
            $table->foreign('invoice_status_id')->references('id')->on('invoice_statuses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
