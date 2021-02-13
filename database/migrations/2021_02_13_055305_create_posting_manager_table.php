<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostingManagerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posting_managers', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('hr_posting_id')->unsigned();
            $table->bigInteger('employee_manager_id')->unsigned();
            
            $table->timestamps();        
            $table->foreign('hr_posting_id')->references('id')->on('hr_postings')->onDelete('cascade');
            $table->foreign('employee_manager_id')->references('id')->on('employee_managers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posting_managers');
    }
}
