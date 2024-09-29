<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('hr_allowances', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('hr_allowance_name_id')->unsigned();
            $table->bigInteger('employee_salary_id')->unsigned();
            $table->integer('amount')->unsigned();
            $table->timestamps();
            $table->foreign('hr_allowance_name_id')->references('id')->on('hr_allowance_names')->onDelete('cascade');
            $table->foreign('employee_salary_id')->references('id')->on('employee_salaries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hr_allowances');
    }
};
