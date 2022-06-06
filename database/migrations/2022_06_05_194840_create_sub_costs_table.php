<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_costs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->id();
            $table->bigInteger('sub_participate_role_id')->unsigned();
            $table->decimal('mm_cost',12,0)->nullable();
            $table->decimal('direct_cost',12,0)->nullable();
            $table->decimal('total_cost',12,0);
            $table->timestamps();
            $table->foreign('sub_participate_role_id')->references('id')->on('sub_participate_roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sub_costs');
    }
}
