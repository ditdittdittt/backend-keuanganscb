<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OnDeleteCascadeToFormPettyCashDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_petty_cash_details', function (Blueprint $table) {
            $table->dropForeign(['form_petty_cash_id']);
            $table->foreign('form_petty_cash_id')->references('id')->on('form_petty_cashes')->onDelete('cascade');
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
            $table->dropForeign(['form_petty_cash_id']);
            $table->foreign('form_petty_cash_id')->references('id')->on('form_petty_cashes');
        });
    }
}
