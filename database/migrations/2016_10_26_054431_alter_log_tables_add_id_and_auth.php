<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterLogTablesAddIdAndAuth_20161026054431 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('order_logs', function(Blueprint $table) {
            $table->integer('order_id')->after('id')->nullable();
            $table->integer('mender')->after('order_id')->nullable();
            $table->integer('version')->after('mender')->nullable();
            $table->string('remark')->after('version')->nullable();
        });
        Schema::table('product_logs', function(Blueprint $table) {
            $table->integer('product_id')->after('id')->nullable();
            $table->integer('mender')->after('product_id')->nullable();
            $table->integer('version')->after('mender')->nullable();
            $table->string('remark')->after('version')->nullable();
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
