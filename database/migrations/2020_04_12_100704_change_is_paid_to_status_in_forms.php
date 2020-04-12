<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIsPaidToStatusInForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_requests', function (Blueprint $table) {
            $table->dropColumn('is_paid');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('form_status');
        });
        Schema::table('form_petty_cashes', function (Blueprint $table) {
            $table->dropColumn('is_paid');
            $table->unsignedBigInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('form_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_requests', function (Blueprint $table) {
            $table->boolean('is_paid')->default(0);
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
        Schema::table('form_petty_cashes', function (Blueprint $table) {
            $table->boolean('is_paid')->default(0);
            $table->dropForeign(['status_id']);
            $table->dropColumn('status_id');
        });
    }
}
