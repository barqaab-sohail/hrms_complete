<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrMonthlyInputProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_monthly_input_projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('hr_monthly_input_id')->unsigned();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->boolean('lock_user')->default(false);
            $table->boolean('lock_manager')->default(false);
            $table->timestamps();
            $table->foreign('hr_monthly_input_id')->references('id')->on('hr_monthly_inputs')->onDelete('cascade');
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
        Schema::dropIfExists('hr_monthly_input_projects');
    }
}
