<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class BudgetCodeForeignKeyInFormPettyCashDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_petty_cash_details', function (Blueprint $table) {
            $table->foreign('budget_code_id')->references('id')->on('budget_codes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_petty_cash_details', function (Blueprint $table) {
            $table->dropForeign(['budget_code_id']);
        });
    }
}
