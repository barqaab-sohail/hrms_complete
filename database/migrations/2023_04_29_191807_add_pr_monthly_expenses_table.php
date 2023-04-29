<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPrMonthlyExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pr_monthly_expenses', function (Blueprint $table) {
            //
            $table->decimal('non_reimbursable_salary', 12, 0)->after('non_salary_expense')->nullable();
            $table->decimal('non_reimbursable_expense', 12, 0)->after('non_salary_expense')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pr_monthly_expenses', function (Blueprint $table) {
            $table->dropColumn('non_reimbursable_salary');
            $table->dropColumn('non_reimbursable_expense');
        });
    }
}
