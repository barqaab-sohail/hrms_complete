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
            $table->tinyInteger('level')->unsigned();
            $table->string('name');
            $table->float('weightage')->unsigned()->nullable();
            $table->bigInteger('belong_to_activity')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('pr_detail_id')->references('id')->on('pr_details')->onDelete('cascade');
            $table->foreign('belong_to_activity')->references('id')->on('pr_progress_activities')->onDelete('cascade');
           
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
