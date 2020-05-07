<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormRequestUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_request_has_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role_name');
            $table->unsignedBigInteger('form_request_id');
            $table->unsignedBigInteger('user_id');
            $table->string('attachment')->nullable();
            $table->foreign('form_request_id')->references('id')->on('form_requests');
            $table->foreign('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_request_has_users');
    }
}
