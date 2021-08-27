<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCvReferences extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cv_references', function (Blueprint $table) {
             $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->string('ref_detail');
            $table->bigInteger('cv_detail_id')->unsigned();
            $table->foreign('cv_detail_id')->references('id')->on('cv_details')->onDelete('cascade');
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
        Schema::dropIfExists('cv_references');
    }
}
