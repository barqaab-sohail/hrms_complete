<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrPositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pr_positions', function (Blueprint $table) {
            $table->decimal('billing', 12, 0)->nullable();
            $table->decimal('total_amount', 12, 0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pr_positions', function (Blueprint $table) {
            $table->dropColumn('billing');
            $table->dropColumn('total_amount');
        });
    }
}
