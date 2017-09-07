<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSaltToCustomersTable_20160630071337 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('salt', 10)->nullable();
            $table->string('real_name', 255)->nullable();
            $table->string('password_question', 255)->nullable();
            $table->string('password_answer', 255)->nullable();
            $table->tinyInteger('sex')->nullable();
            $table->date('birthday')->nullable();
            $table->string('qq', 20)->nullable();
            $table->string('office_phone', 20)->nullable();
            $table->string('home_phone', 20)->nullable();
            $table->tinyInteger('is_validated')->nullable();
            $table->integer('address_id')->nullable();
            $table->datetime('last_login')->nullable();
            $table->string('last_ip')->nullable();
            $table->integer('visit_count')->nullable();
            $table->string('alias', 255)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('salt');
            $table->dropColumn('real_name');
            $table->dropColumn('password_question');
            $table->dropColumn('password_answer');
            $table->dropColumn('sex');
            $table->dropColumn('birthday');
            $table->dropColumn('qq');
            $table->dropColumn('office_phone');
            $table->dropColumn('home_phone');
            $table->dropColumn('mobile_phone');
            $table->dropColumn('is_validated');
            $table->dropColumn('address_id');
            $table->dropColumn('last_login');
            $table->dropColumn('last_ip');
            $table->dropColumn('visit_count');
            $table->dropColumn('alias');
        });
    }
}
