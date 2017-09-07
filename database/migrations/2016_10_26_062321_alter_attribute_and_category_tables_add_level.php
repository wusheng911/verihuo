<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAttributeAndCategoryTablesAddLevel_20161026062321 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_categories', function(Blueprint $table) {
            $table->integer('level')->after('pid')->nullable();
        });
        schema::table('content_categories', function(blueprint $table) {
            $table->integer('level')->after('pid')->nullable();
        });
        schema::table('shop_attributes', function(blueprint $table) {
            $table->integer('level')->after('pid')->nullable();
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
