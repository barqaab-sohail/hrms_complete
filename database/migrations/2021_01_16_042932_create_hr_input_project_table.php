<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrInputProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_input_projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('hr_input_month_id')->unsigned();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->boolean('lock_user')->default(false);
            $table->boolean('lock_manager')->default(false);
            $table->timestamps();
            $table->foreign('hr_input_month_id')->references('id')->on('hr_input_months')->onDelete('cascade');
            $table->foreign('pr_detail_id')->references('id')->on('pr_details');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_input_projects');
    }
}
