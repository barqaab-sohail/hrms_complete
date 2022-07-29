<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_cvs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('sub_position_id')->unsigned();
            $table->string('file_name');
            $table->string('extension');
            $table->string('path');
            $table->string('size',20);
            $table->mediumText('content');
            $table->timestamps();
            $table->foreign('sub_position_id')->references('id')->on('sub_positions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_cvs');
    }
}
