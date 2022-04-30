<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrAchivedProgresse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_achived_progresse', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->bigInteger('pr_progress_activity_id')->unsigned();
            $table->tinyInteger('percentage_complete')->unsigned();
            $table->date('date');
            $table->timestamps();
            $table->foreign('pr_detail_id')->references('id')->on('pr_details')->onDelete('cascade');
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
        Schema::dropIfExists('pr_achived_progresse');
    }
}
