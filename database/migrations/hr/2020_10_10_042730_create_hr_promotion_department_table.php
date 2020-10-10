<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrPromotionDepartmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_promotion_departments', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_promotion_id')->unsigned();
            $table->bigInteger('hr_department_id')->unsigned();
            $table->timestamps();
            $table->foreign('hr_promotion_id')->references('id')->on('hr_promotions')->onDelete('cascade');
            $table->foreign('hr_department_id')->references('id')->on('hr_departments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_promotion_departments');
    }
}
