<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrIndividualProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pr_individual_projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->string('name', 512)->unique();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->date('commencement_date');
            $table->date('contractual_completion_date');
            $table->date('actual_completion_date')->nullable();
            $table->string('ind_project_no', 15)->unique()->nullable();
            $table->timestamps();
            $table->foreign('pr_detail_id')->references('id')->on('pr_details')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pr_individual_projects');
    }
}
