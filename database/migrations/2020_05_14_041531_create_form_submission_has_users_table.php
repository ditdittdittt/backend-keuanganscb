<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSubmissionHasUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_submission_has_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('role_name');
            $table->unsignedBigInteger('form_submission_id');
            $table->unsignedBigInteger('user_id');
            $table->string('attachment')->nullable();
            $table->foreign('form_submission_id')->references('id')->on('form_submissions')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('form_submission_has_users');
    }
}
