<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrCosts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_costs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->bigInteger('pr_cost_type_id')->unsigned();
            $table->decimal('mm_cost',12,0)->nullable();
            $table->decimal('direct_cost',12,0)->nullable();
            $table->decimal('contingency_cost',12,0)->nullable();
            $table->decimal('sales_tax',12,0)->nullable();
            $table->decimal('total_cost',12,0);
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('pr_cost_type_id')->references('id')->on('pr_cost_types');
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
        Schema::dropIfExists('pr_costs');
    }
}
