<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubDescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_descriptions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('submission_id')->unsigned();
            $table->bigInteger('sub_status_id')->unsigned()->nullable();
            $table->bigInteger('sub_financial_type_id')->unsigned()->nullable();
            $table->bigInteger('sub_cv_format_id')->unsigned()->nullable();
            $table->bigInteger('sub_evaluation_type_id')->unsigned()->nullable();
            $table->date('technical_opening_date')->nullable();
            $table->date('financial_opening_date')->nullable();
            $table->tinyInteger('technical_weightage')->unsigned()->nullable();
            $table->tinyInteger('financial_weightage')->unsigned()->nullable();
            $table->decimal('total_marks',4,0)->unsigned()->nullable();
            $table->decimal('passing_marks',4,0)->unsigned()->nullable();
            $table->text('scope_of_services')->nullable();
            $table->text('scope_of_work')->nullable();
            $table->timestamps();
            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreign('sub_status_id')->references('id')->on('sub_statuses');
            $table->foreign('sub_financial_type_id')->references('id')->on('sub_financial_types');
            $table->foreign('sub_cv_format_id')->references('id')->on('sub_cv_formats');
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
        Schema::dropIfExists('sub_descriptions');
    }
}
