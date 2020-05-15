<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullableDateAttribute extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('form_requests', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->string('date')->nullable();
        });
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->string('date')->nullable();
        });
        Schema::table('form_petty_cashes', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->string('date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('form_requests', function (Blueprint $table) {
            $table->dropColumn('date');
        });
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->dropColumn('date');
        });
        Schema::table('form_petty_cashes', function (Blueprint $table) {
            $table->dropColumn('date');
        });
    }
}
