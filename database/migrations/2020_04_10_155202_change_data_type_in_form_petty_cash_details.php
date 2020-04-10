<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDataTypeInFormPettyCashDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_petty_cash_details', function (Blueprint $table) {
            $table->dropColumn('budget_code');
            $table->unsignedBigInteger('budget_code_id')->nullable();
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
            $table->dropColumn('budget_code_id');
            $table->string('budget_code');
        });
    }
}
