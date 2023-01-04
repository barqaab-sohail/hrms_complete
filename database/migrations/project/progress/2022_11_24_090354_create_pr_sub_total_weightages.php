<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrSubTotalWeightages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_sub_total_weightages', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_progress_activity_id')->unsigned();
            $table->float('total_weightage')->unsigned();
            $table->timestamps();
            $table->foreign('pr_progress_activity_id')->references('id')->on('pr_progress_activities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_sub_total_weightages');
    }
}
