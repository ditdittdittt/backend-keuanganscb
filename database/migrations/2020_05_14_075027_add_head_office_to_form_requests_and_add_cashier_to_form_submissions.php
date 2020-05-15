<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddHeadOfficeToFormRequestsAndAddCashierToFormSubmissions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_requests', function (Blueprint $table) {
            $table->boolean('is_confirmed_head_office')->default(0);
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->boolean('is_confirmed_cashier')->default(0);
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
            $table->dropColumn('is_confirmed_head_office');
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn('is_confirmed_cashier');
        });
    }
}
