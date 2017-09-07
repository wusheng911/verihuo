<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderOauthTableChangeColumns_20161209122108 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->datetime('delivered_at')->timestamp()->nullable()->default(null)->change();
            $table->datetime('completed_at')->timestamp()->nullable()->change();
            $table->smallInteger('payment_method_id')->tinyInteger()->change()->comment = '';
            $table->string('payment_method_name')->change()->comment = '';
        });
        Schema::table('oauth', function (Blueprint $table) {
            $table->string('wechat_unionid')->after('open_id')->nullable();
        });
        Schema::table('shop_categories', function (Blueprint $table) {
            $table->softDeletes()->after('image');
        });
        Schema::table('shop_attributes', function (Blueprint $table) {
            $table->softDeletes()->after('pos');
        });
        Schema::table('shop_attribute_values', function (Blueprint $table) {
            $table->softDeletes()->after('pos');
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->string('webchat')->after('qq')->nullable();
            $table->integer('customer_id')->after('birthday')->nullable()->comment = '关联用户id号';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
