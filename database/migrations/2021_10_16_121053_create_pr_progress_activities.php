<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrProgressActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_progress_activities', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->string('name');
            $table->tinyInteger('percentage_weightage')->unsigned();
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
        Schema::dropIfExists('pr_progress_activities');
    }
}
