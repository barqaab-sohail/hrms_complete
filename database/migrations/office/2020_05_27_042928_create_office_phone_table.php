<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfficePhoneTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('office_phones', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('office_detail_id')->unsigned();
            $table->string('phone_no');
            $table->bigInteger('belong_to')->nullable()->unsigned();
            $table->timestamps();
            $table->foreign('office_detail_id')->references('id')->on('office_details')->onDelete('cascade');
            $table->foreign('belong_to')->references('id')->on('hr_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('office_phones');
    }
}
