<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFormRequestsAttribut extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_requests', function (Blueprint $table) {
            $table->boolean('is_confirmed_pic');
            $table->boolean('is_confirmed_verificator');
            $table->boolean('is_confirmed_head_dept');
            $table->boolean('is_confirmed_cashier');
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
            $table->dropColumn('is_confirmed_pic');
            $table->dropColumn('is_confirmed_verificator');
            $table->dropColumn('is_confirmed_head_dept');
            $table->dropColumn('is_confirmed_cashier');
        });
    }
}
