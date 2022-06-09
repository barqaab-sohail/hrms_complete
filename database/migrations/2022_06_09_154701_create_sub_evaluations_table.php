<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubEvaluationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_evaluations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('submission_id')->unsigned();
            $table->bigInteger('sub_evaluation_type_id')->unsigned();
            $table->tinyInteger('technical_weightage')->unsigned();
            $table->tinyInteger('financial_weightage')->unsigned();
            $table->tinyInteger('pass_marks')->unsigned();
            $table->timestamps();
            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreign('sub_evaluation_type_id')->references('id')->on('sub_evaluation_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_evaluations');
    }
}
