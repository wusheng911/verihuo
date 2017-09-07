<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCustomerOuthTablesModColumns_20161214134325 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('nick_name')->after('user_name')->nullable();
            $table->renameColumn('webchat', 'wechat')->change();
        });
        Schema::table('oauth', function (Blueprint $table) {
            $table->text('info')->nullable()->change();
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
    }
}
