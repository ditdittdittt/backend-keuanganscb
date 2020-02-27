<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormPettyCashDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_petty_cash_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->unsignedBigInteger('form_petty_cash_id');
            $table->foreign('form_petty_cash_id')->on('form_petty_cashes')->references('id');
            $table->unsignedInteger('budget_code');
            $table->string('budget_name');
            $table->decimal('nominal', 13, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('form_petty_cash_details');
    }
}
