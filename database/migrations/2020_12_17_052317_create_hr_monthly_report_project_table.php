<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrMonthlyReportProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_monthly_report_projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('hr_monthly_report_id')->unsigned();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->boolean('is_locak')->default(false);
            $table->timestamps();
            $table->foreign('hr_monthly_report_id')->references('id')->on('hr_monthly_reports')->onDelete('cascade');
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
        Schema::dropIfExists('hr_monthly_report_projects');
    }
}
