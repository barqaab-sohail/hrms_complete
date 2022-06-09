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
            $table->bigInteger('sub_status_id')->unsigned();
            $table->bigInteger('sub_financial_type_id')->unsigned();
            $table->bigInteger('sub_cv_format_id')->unsigned();
            $table->timestamps();
            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreign('sub_status_id')->references('id')->on('sub_statuses');
            $table->foreign('sub_financial_type_id')->references('id')->on('sub_financial_types');
            $table->foreign('sub_cv_format_id')->references('id')->on('sub_cv_formats');

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
