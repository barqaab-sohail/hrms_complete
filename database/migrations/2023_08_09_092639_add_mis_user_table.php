<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMisUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mis_users', function (Blueprint $table) {
            $table->bigInteger('hr_employee_id')->unsigned()->nullable();
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
        Schema::table('mis_users', function (Blueprint $table) {
            $table->dropForeign('mis_users_hr_employee_id_foreign');
            $table->dropColumn('hr_employee_id');
        });
    }
}
