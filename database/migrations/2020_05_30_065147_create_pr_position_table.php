<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrPositionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_positions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->bigInteger('pr_position_type_id')->unsigned();
            $table->bigInteger('hr_designation_id')->unsigned();
            $table->bigInteger('hr_employee_id')->nullable()->unsigned();
            $table->string('nominated_person');
            $table->decimal('total_mm', 6, 3);
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->foreign('pr_detail_id')->references('id')->on('pr_details')->onDelete('cascade');
            $table->foreign('pr_position_type_id')->references('id')->on('pr_position_types');
            $table->foreign('hr_designation_id')->references('id')->on('hr_designations');
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
        Schema::dropIfExists('pr_positions');
    }
}
