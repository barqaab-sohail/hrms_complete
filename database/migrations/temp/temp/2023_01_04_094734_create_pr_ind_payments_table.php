<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrIndPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_ind_payments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_individual_project_id')->unsigned();
            $table->bigInteger('pr_ind_invoice_id')->unsigned();
            $table->decimal('amount', 12, 0);
            $table->date('payment_date');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->foreign('pr_individual_project_id')->references('id')->on('pr_individual_projects')->onDelete('cascade');
            $table->foreign('pr_ind_invoice_id')->references('id')->on('pr_ind_invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_ind_payments');
    }
}
