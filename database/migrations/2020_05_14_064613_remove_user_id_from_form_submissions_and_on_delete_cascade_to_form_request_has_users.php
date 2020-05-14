<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUserIdFromFormSubmissionsAndOnDeleteCascadeToFormRequestHasUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('form_request_has_users', function (Blueprint $table) {
            $table->dropForeign(['form_request_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('form_request_id')->references('id')->on('form_requests')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_request_has_users', function (Blueprint $table) {
            $table->dropForeign(['form_request_id']);
            $table->dropForeign(['user_id']);
            $table->foreign('form_request_id')->references('id')->on('form_requests');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }
}
