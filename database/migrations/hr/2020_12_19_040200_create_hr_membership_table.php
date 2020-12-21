<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrMembershipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_memberships', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('membership_id')->unsigned();
            $table->foreign('membership_id')->references('id')->on('memberships');

            $table->bigInteger('hr_employee_id')->unsigned();
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees')->onDelete('cascade');

            $table->string('membership_no',20)->nullable();
            $table->date('expiry')->nullable();
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
        Schema::dropIfExists('hr_memberships');
    }
}
