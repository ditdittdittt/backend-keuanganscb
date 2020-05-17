<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NullableNotesInForms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_requests', function (Blueprint $table) {
            $table->string('notes')->nullable()->change();
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->string('notes')->nullable()->change();
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
            $table->string('notes')->change();
        });

        Schema::table('form_submissions', function (Blueprint $table) {
            $table->string('notes')->change();
        });
    }
}
