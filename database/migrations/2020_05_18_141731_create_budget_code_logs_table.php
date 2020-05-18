<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBudgetCodeLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('budget_code_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('budget_code_id');
            $table->string('number');
            $table->string('type');
            $table->decimal('nominal', 13, 2);
            $table->unsignedBigInteger('user_id');
            $table->foreign('budget_code_id')->references('id')->on('budget_codes');
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
        Schema::dropIfExists('budget_code_logs');
    }
}
