<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveBudgetNameInFormPettyCashDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_petty_cash_details', function (Blueprint $table) {
            $table->dropColumn('budget_name');
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
            $table->string('budget_name');
        });
    }
}
