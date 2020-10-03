<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrPostingProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_posting_projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_posting_id')->unsigned();
            $table->bigInteger('pr_detail_id')->unsigned();
            $table->timestamps();
            $table->foreign('hr_posting_id')->references('id')->on('hr_postings')->onDelete('cascade');
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
        Schema::dropIfExists('hr_posting_projects');
    }
}
