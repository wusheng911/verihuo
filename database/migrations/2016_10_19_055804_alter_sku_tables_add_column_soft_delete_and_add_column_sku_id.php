<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterSkuTablesAddColumnSoftDeleteAndAddColumnSkuId_20161019055804 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('skus', function(Blueprint $table) {
            $table->softDeletes()->after('attribute_values');
            $table->string('sale_attribute_values')->after('prime_price');
        });
        Schema::table('sku_attributes', function(Blueprint $table) {
            $table->integer('sku_id')->after('id');
            $table->tinyInteger('is_sell')->after('attribute_id');
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
