<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_submissions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('form_request_id');
            $table->foreign('form_request_id')->references('id')->on('form_requests');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('date');
            $table->decimal('used', 13, 2);
            $table->decimal('balanced', 13, 2);
            $table->string('allocation');
            $table->string('notes');
            $table->boolean('is_confirmed_pic');
            $table->boolean('is_confirmed_verificator');
            $table->boolean('is_confirmed_head_dept');
            $table->boolean('is_confirmed_head_office');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_submissions');
    }
}
