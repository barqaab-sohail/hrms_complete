<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->bigIncrements('id');
            $table->bigInteger('eoi_reference_no')->unsigned()->nullable();
            $table->bigInteger('sub_type_id')->unsigned();
            $table->bigInteger('client_id')->unsigned();
            $table->bigInteger('sub_division_id')->unsigned();
            $table->string('submission_no',15)->unique();
            $table->string('project_name',512)->unique();
            $table->text('comments')->nullable();
            $table->foreign('sub_type_id')->references('id')->on('sub_types');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->foreign('sub_division_id')->references('id')->on('pr_divisions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}
