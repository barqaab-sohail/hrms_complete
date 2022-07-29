<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubMultiCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_multi_currencies', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('sub_competitor_id')->unsigned();
            $table->bigInteger('currency_id')->unsigned();
            $table->date('conversion_date');
            $table->decimal('conversion_rate',12,4);
            $table->decimal('currency_price',12,0);
            $table->timestamps();
            $table->foreign('sub_competitor_id')->references('id')->on('sub_competitors')->onDelete('cascade');
            $table->foreign('currency_id')->references('id')->on('currencies');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_multi_currencies');
    }
}
