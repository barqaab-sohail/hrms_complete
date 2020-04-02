<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_details', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->bigInteger('contract_type_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->date('commencement_date');
            $table->date('contractual_completion_date');
            $table->date('actual_completion_date');
            $table->bigInteger('pr_status_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();
            $table->string('share')->nullable();
            $table->timestamps();

            $table->foreign('contract_type_id')->references('id')->on('contract_types');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('pr_status_id')->references('id')->on('pr_statuses');
             $table->foreign('role_id')->references('id')->on('roles');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_details');
    }
}
