<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSomeDefaultValue extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('form_petty_cashes', function (Blueprint $table) {
            $table->boolean('is_confirmed_pic')->default(false)->change();
            $table->boolean('is_confirmed_manager_ops')->default(false)->change();
            $table->boolean('is_confirmed_cashier')->default(false)->change();
        });
        Schema::table('form_requests', function (Blueprint $table) {
            $table->boolean('is_confirmed_pic')->default(false)->change();
            $table->boolean('is_confirmed_verificator')->default(false)->change();
            $table->boolean('is_confirmed_head_dept')->default(false)->change();
            $table->boolean('is_confirmed_cashier')->default(false)->change();
        });
        Schema::table('form_submissions', function (Blueprint $table) {
            $table->boolean('is_confirmed_pic')->default(false)->change();
            $table->boolean('is_confirmed_verificator')->default(false)->change();
            $table->boolean('is_confirmed_head_dept')->default(false)->change();
            $table->boolean('is_confirmed_head_office')->default(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('form_petty_cashes', function (Blueprint $table) {
            $table->boolean('is_confirmed_pic')->change();
            $table->boolean('is_confirmed_manager_ops')->change();
            $table->boolean('is_confirmed_cashier')->change();
        });
    }
}
