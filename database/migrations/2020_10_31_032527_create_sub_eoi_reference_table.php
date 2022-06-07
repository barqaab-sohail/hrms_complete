<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubEoiReferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_eoi_references', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('submission_id')->unsigned();
            $table->bigInteger('eoi_reference_id')->unsigned();
            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreign('eoi_reference_id')->references('id')->on('submissions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_eoi_references');
    }
}
