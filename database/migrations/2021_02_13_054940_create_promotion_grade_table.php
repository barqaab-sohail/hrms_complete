<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionGradeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_grades', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('hr_promotion_id')->unsigned();
            $table->bigInteger('employee_grade_id')->unsigned();
            
            $table->timestamps();        
            $table->foreign('hr_promotion_id')->references('id')->on('hr_promotions')->onDelete('cascade');
            $table->foreign('employee_grade_id')->references('id')->on('employee_grades');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_grades');
    }
}
