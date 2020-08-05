<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHrDocumentationProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hr_documentation_projects', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('hr_documentation_id')->unsigned();
            $table->bigInteger('pr_document_id')->unsigned();
            $table->timestamps();
            $table->foreign('hr_documentation_id')->references('id')->on('hr_documentations')->onDelete('cascade');
            $table->foreign('pr_document_id')->references('id')->on('pr_documents');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hr_documentation_projects');
    }
}
