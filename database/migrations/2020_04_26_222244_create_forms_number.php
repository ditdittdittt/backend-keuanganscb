<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormsNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_requests', function (Blueprint $table) {
            $table->string('number');
        });
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->string('number');
        });
        Schema::table('form_petty_cashes', function (Blueprint $table) {
            $table->string('number');
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
            $table->dropColumn('number');
        });
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn('number');
        });
        Schema::table('form_petty_cashes', function (Blueprint $table) {
            $table->dropColumn('number');
        });
    }
}
