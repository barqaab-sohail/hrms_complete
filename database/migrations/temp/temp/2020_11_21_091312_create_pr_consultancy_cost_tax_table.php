<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrConsultancyCostTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_consultancy_cost_taxes', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_consultancy_cost_id')->unsigned();
            $table->integer('tax_cost')->unsigned();
            $table->timestamps();
            $table->foreign('pr_consultancy_cost_id')->references('id')->on('pr_consultancy_costs')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_consultancy_cost_taxes');
    }
}
