<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubContactTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_contacts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('submission_id')->unsigned();
            $table->string('landline',20)->nullable(); 
            $table->string('mobile',20)->nullable(); 
            $table->string('fax',20)->nullable(); 
            $table->string('email',50)->nullable();
            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
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
        Schema::dropIfExists('sub_contacts');
    }
}
