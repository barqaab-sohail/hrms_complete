<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSsContactOfficeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ss_contact_offices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('ss_contact_id')->unsigned();
            $table->string('office_phone')->nullable();
            $table->string('office_fax')->nullable();
            $table->string('office_address')->nullable();
            $table->timestamps();
            $table->foreign('ss_contact_id')->references('id')->on('ss_contacts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ss_contact_offices');
    }
}
