<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class OnDeleteCascadeFormDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_request_details', function (Blueprint $table) {
            $table->dropForeign(['form_request_id']);
            $table->foreign('form_request_id')->references('id')->on('form_requests')->onDelete('cascade');
        });
        Schema::table('form_submission_details', function (Blueprint $table) {
            $table->dropForeign(['form_submission_id']);
            $table->foreign('form_submission_id')->references('id')->on('form_submissions')->onDelete('cascade');
        });
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
        Schema::table('form_request_details', function (Blueprint $table) {
            $table->dropForeign(['form_request_id']);
            $table->foreign('form_request_id')->references('id')->on('form_requests');
        });
        Schema::table('form_submission_details', function (Blueprint $table) {
            $table->dropForeign(['form_submission_id']);
            $table->foreign('form_submission_id')->references('id')->on('form_submissions');
        });
        Schema::table('form_petty_cash_details', function (Blueprint $table) {
            $table->dropForeign(['form_petty_cash_id']);
            $table->foreign('form_petty_cash_id')->references('id')->on('form_petty_cashes');
        });
    }
}
