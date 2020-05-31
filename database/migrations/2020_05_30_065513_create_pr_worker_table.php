<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrWorkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_workers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_invoice_id')->unsigned();
            $table->bigInteger('hr_employee_id')->unsigned();
            $table->bigInteger('office_id')->unsigned();
            $table->bigInteger('pr_position_id')->unsigned();
            $table->float('current_mm',8,3); //first is total number of numbers and second is decimal precision
            $table->float('utilized_mm',8,3); //first is total number of numbers and second is decimal precision
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('pr_invoice_id')->references('id')->on('pr_invoices')->onDelete('cascade');
            $table->foreign('hr_employee_id')->references('id')->on('hr_employees');
            $table->foreign('office_id')->references('id')->on('offices');
            $table->foreign('pr_position_id')->references('id')->on('pr_positions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_workers');
    }
}
