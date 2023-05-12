<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLedgerActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledger_activities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->date('voucher_date');
            $table->string('voucher_no', 25);
            $table->date('reference_date')->nullable();
            $table->string('reference_no', 25)->nullable();
            $table->string('description');
            $table->float('debit', 12, 0);
            $table->float('credit', 12, 0);
            $table->float('balance', 12, 0);
            $table->string('remarks');
            $table->timestamps();
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
        Schema::dropIfExists('ledger_activities');
    }
}
