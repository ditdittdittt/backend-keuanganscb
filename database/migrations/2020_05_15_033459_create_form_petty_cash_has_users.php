<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormPettyCashHasUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_petty_cash_has_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role_name');
            $table->unsignedBigInteger('form_petty_cash_id');
            $table->unsignedBigInteger('user_id');
            $table->string('attachment')->nullable();
            $table->foreign('form_petty_cash_id')->references('id')->on('form_petty_cashes')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::table('form_petty_cashes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_petty_cash_has_users');
    }
}
