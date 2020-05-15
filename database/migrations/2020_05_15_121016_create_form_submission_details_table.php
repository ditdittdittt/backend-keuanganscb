<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormSubmissionDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_submission_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('form_submission_id');
            $table->unsignedBigInteger('budget_code_id');
            $table->decimal('used', 13, 2);
            $table->decimal('balance', 13, 2);
            $table->foreign('form_submission_id')->references('id')->on('form_submissions');
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
        Schema::dropIfExists('form_submission_details');
    }
}
