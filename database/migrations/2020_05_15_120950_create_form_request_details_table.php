<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormRequestDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_request_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('form_request_id');
            $table->unsignedBigInteger('budget_code_id');
            $table->decimal('nominal', 13, 2);
            $table->foreign('form_request_id')->references('id')->on('form_requests');
            $table->foreign('budget_code_id')->references('id')->on('budget_codes');
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
        Schema::dropIfExists('form_request_details');
    }
}
