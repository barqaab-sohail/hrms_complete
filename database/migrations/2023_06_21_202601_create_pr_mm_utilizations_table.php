<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrMmUtilizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_mm_utilizations', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->bigInteger('pr_position_id')->unsigned();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->date('month_year');
            $table->decimal('man_month', 3, 1);
            $table->decimal('billing_rate', 12, 0);
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('pr_detail_id')->references('id')->on('pr_details')->onDelete('cascade');
            $table->foreign('pr_position_id')->references('id')->on('pr_positions');
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
        Schema::dropIfExists('pr_mm_utilizations');
    }
}
