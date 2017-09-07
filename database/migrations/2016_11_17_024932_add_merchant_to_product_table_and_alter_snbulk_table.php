<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMerchantToProductTableAndAlterSnbulkTable_20161117024932 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->integer('merchant_id')->after('id')->default(0);
        });
        Schema::table('skus', function (Blueprint $table) {
            $table->integer('merchant_id')->after('product_id')->default(0);
        });
        Schema::table('order_pool', function (Blueprint $table) {
            $table->string('used')->nullable()->change();
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
