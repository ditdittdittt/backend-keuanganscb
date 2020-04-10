<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAttributesToFormRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_requests', function (Blueprint $table) {
            $table->string('bank_name')->nullable();
            $table->string('bank_code')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_owner')->nullable();
            $table->boolean('is_paid')->default(0);
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
            $table->dropColumn('bank_name');
            $table->dropColumn('bank_code');
            $table->dropColumn('account_number');
            $table->dropColumn('account_owner');
            $table->dropColumn('is_paid');
        });
    }
}
