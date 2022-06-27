<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOfficesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->unique('name');
            $table->date('establish_date')->nullable();
            $table->string('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->bigInteger('city_id')->unsigned()->nullable();
            $table->bigInteger('state_id')->unsigned()->nullable();
            $table->bigInteger('country_id')->unsigned()->nullable();
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offices', function (Blueprint $table) {
            $table->dropUnique('offices_name_unique');
            $table->dropColumn('establish_date');
            $table->dropColumn('address');
            $table->dropColumn('is_active');
            $table->dropForeign('offices_city_id_foreign');
            $table->dropForeign('offices_state_id_foreign');
            $table->dropForeign('offices_country_id_foreign');
            $table->dropColumn('city_id');
            $table->dropColumn('state_id');
            $table->dropColumn('country_id');
        });
    }
}
