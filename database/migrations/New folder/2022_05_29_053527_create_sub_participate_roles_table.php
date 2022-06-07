<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubParticipateRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_participate_roles', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('submission_id')->unsigned();
            $table->bigInteger('partner_id')->unsigned();
            $table->bigInteger('pr_role_id')->unsigned();
            $table->string('share')->nullable();
             $table->timestamps();
            $table->foreign('submission_id')->references('id')->on('submissions')->onDelete('cascade');
            $table->foreign('partner_id')->references('id')->on('partners');
            $table->foreign('pr_role_id')->references('id')->on('pr_roles');

            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_participate_roles');
    }
}
