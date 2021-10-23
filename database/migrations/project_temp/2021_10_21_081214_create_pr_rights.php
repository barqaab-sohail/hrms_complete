<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrRights extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_rights', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->tinyInteger('progress')->unsigned()->default(0)->comment('0 No Rights, 1 View Rights, 2 Add Rights, 3 Edit Rights, 4 Delete Rights' );
            $table->tinyInteger('invoice')->unsigned()->default(0)->comment('0 No Rights, 1 View Rights, 2 Add Rights, 3 Edit Rights, 4 Delete Rights' );
            $table->timestamps();
            $table->foreign('pr_detail_id')->references('id')->on('pr_details')->onDelete('cascade');
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_rights');
    }
}
