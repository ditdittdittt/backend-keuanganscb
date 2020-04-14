<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBudgetCodeIdToFormRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_requests', function (Blueprint $table) {
            $table->unsignedBigInteger('budget_code_id')->default(1);
            $table->foreign('budget_code_id')->references('id')->on('budget_codes');
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
            $table->dropForeign(['budget_code_id']);
            $table->dropColumn('budget_code_id');
        });
    }
}
