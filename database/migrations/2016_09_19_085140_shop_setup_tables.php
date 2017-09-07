<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ShopSetupTables_20160919085140 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 商家实体
        if (!Schema::hasTable('merchants')){
            Schema::create('merchants', function(Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('description');
                $table->string('logo');
            });
        }
        if (!Schema::hasTable('zones')){
            Schema::create('zones', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('pid')->unsigned()->nullable();
                $table->tinyInteger('level')->default(0)->comment = '0:国家, 1:省/直辖市/自治区, 2:市, 3:区县, 4:街道';
                $table->integer('country_id')->unsigned()->nullable()->comment = '关联到zones表';
                $table->string('country')->default('');
                $table->integer('province_id')->unsigned()->nullable()->comment = '关联到zones表';
                $table->string('province')->default('');
                $table->integer('city_id')->unsigned()->nullable()->comment = '关联到zones表';
                $table->string('city')->default('');
                $table->integer('district_id')->unsigned()->nullable()->comment = '关联到zones表';
                $table->string('district')->default('');
                $table->integer('town_id')->unsigned()->nullable()->comment = '关联到zones表';
                $table->string('town')->default('');
            });
        }
        if (!Schema::hasTable('addresses')){
            Schema::create('addresses', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('customer_id')->unsigned()->nullable();
                $table->integer('zone_id')->unsigned()->nullable();
                $table->string('town')->nullable();
                $table->string('details')->nullable();
                $table->tinyInteger('is_default')->default(0);
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('coupons')){
            Schema::create('coupons', function(Blueprint $table) {
                $table->increments('id');
                $table->string('sn')->comment = '序列号';
                $table->integer('customer_id')->unsigned()->nullable();// 空则表示不属于特定用户
                $table->integer('count')->default(0)->comment = '可以使用数量';
                $table->tinyInteger('type')->default(0)->comment = '1:直接抵扣, 2:原价打折';
                $table->string('type_value')->default('')->comment = '优惠券相应类型补充值';
                $table->tinyInteger('status')->default(0)->comment = '0:未激活, 1:有效已经激活';
                $table->string('description');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('paid_logs')){
            Schema::create('paid_logs', function(Blueprint $table) {
                $table->increments('id');
                $table->tinyInteger('payment_method_id')->default(1)->comment = '1:支付宝, 2:移动支付宝, 3:微信';
                $table->tinyInteger('payment_method_name')->default(1)->comment = '1:支付宝, 2:移动支付宝, 3:微信';
                $table->string('paid_transaction', 50)->nullable()->comment = '交易流水号';
                $table->tinyInteger('paid_status')->default(0)->comment = '1:支付中, 2:支付成功, 3:支付出错';
                $table->text('paid_info')->default('');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('order_status')){
            Schema::create('order_status', function(Blueprint $table) {
                $table->tinyInteger('id');
                $table->string('description');
            });
        }
        if (!Schema::hasTable('orders')){
            Schema::create('orders', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('customer_id')->unsigned();
                $table->string('customer_name')->default('');
                $table->integer('consignee_zone_id')->default(0)->comment = '收货地点';
                $table->string('consignee_name')->default('');
                $table->string('consignee_phone')->default('');
                $table->string('consignee_address')->default('');
                $table->tinyInteger('status')->default('1')->comment = '1:新订单, 2:订单完成, 3:订单已确认, 4:备货中, 5:已发货, 6:已签收, 7:已取消; 具体参考order_status表';
                $table->string('shipper_name')->default('')->comment = '发货人';
                $table->string('shipper_phone')->default('');
                $table->string('shipper_address')->default('');
                $table->timestamp('delivered_at')->comment = '发货时间';
                $table->timestamp('completed_at')->comment = '订单完成(确认收货, 客服操作等)';
                $table->string('logistic_company')->default('');
                $table->string('logistic_no')->default('');
                $table->tinyInteger('logistic_status')->default(1);
                $table->decimal('total', 8, 2)->comment = '订单总额';
                $table->decimal('prime_total', 8, 2)->comment = '订单原价, 所有商品和运费不含折扣';
                $table->decimal('discount', 8, 2)->comment = '订单折扣价格， total = prime_total - discount';
                $table->tinyInteger('payment_method_id')->default(1)->comment = '1:支付宝, 2:移动支付宝, 3:微信';
                $table->tinyInteger('payment_method_name')->default(1)->comment = '1:支付宝, 2:移动支付宝, 3:微信';
                $table->tinyInteger('paid_status')->default(0)->comment = '0:未支付, 1:支付中, 2:支付完成, 3:支付出错';
                $table->timestamp('paid_at')->nullable()->comment = '支付时间';
                $table->string('paid_transaction', 50)->nullable()->comment = '交易流水号';
                $table->decimal('paid_total', 8, 2)->default(0)->comment = '已经支付金额';
                $table->string('book_agent')->default('')->comment = '下单客户代理';
                $table->string('book_ip')->default('')->comment = '下单ip';
                $table->string('remark')-> comment = '客户下单留言';
                $table->integer('merchant_id')->unsigned()->default(0);
                $table->string('tips')-> comment = '商户为订单建议';
                $table->softDeletes();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('order_skus')){
            Schema::create('order_skus', function(Blueprint $table) {
                $table->integer('order_id')->default(0);
                $table->integer('sku_id')->default(0);
                $table->string('sku_sn')->default('');
                $table->integer('product_id')->default(0);
                $table->string('product_name')->default('');
                $table->integer('quantity')->default(0)->comment = '购买数量';
                $table->decimal('market_price', 8, 2)->default(0.00);
                $table->decimal('total', 8, 2)->default(0.00)->comment = '商品总价, total = market_price * quantity';
                $table->decimal('prime_price', 8, 2)->default(0.00)->comment = '商品成本价';
            });
        }
        if (!Schema::hasTable('order_logs')){
            Schema::create('order_logs', function(Blueprint $table) {
                $table->increments('id');
                $table->text('info');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('carts')){
            Schema::create('carts', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('customer_id')->unsigned()->default(0);
                $table->integer('merchant_id')->unsigned()->default(0);
                $table->string('session_id')->default('');
                $table->integer('sku_id')->unsigned()->default(0);
                $table->integer('quantity')->default('1');
                $table->decimal('market_price', 8, 2)->default(0.00);
                $table->integer('product_id')->unsigned()->default(0);
                $table->string('product_name')->default('');
                $table->string('product_info')->default('');
                $table->string('image')->default('');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('products')){
            Schema::create('products', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('shop_category_id')->unsigned()->default(0);
                $table->string('name', 100);
                $table->string('sn', 60);
                $table->string('info', 512)->comment = '商品简单说明';
                $table->text('html_info')->comment = '详尽的商品描述';
                $table->string('area', 255)->default('')->comment = '产品产地';
                $table->string('package', 255)->default('')->comment = '产品包装';
                $table->decimal('show_price', 8, 2)->default(0.0)->comment = '显示卖出价';
                $table->decimal('show_min_price', 8, 2)->default(0.0)->comment = '显示卖出价';
                $table->decimal('show_max_price', 8, 2)->default(0.0)->comment = '显示卖出价';
                $table->tinyInteger('status')->default(0)->comment = '商品状态 0:无效商品, 1:未审核, 2:已审核';
                $table->tinyInteger('is_available')->default(0)->comment = '是否上架, 1:上架, 2:下架';
                $table->softDeletes();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('product_images')){
            Schema::create('product_images', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->default(0);
                $table->string('image')->default('');
                $table->integer('pos')->default(0);
                $table->tinyInteger('is_first')->default(0)->comment = '是否首张';
                $table->tinyInteger('device')->default(0)->comment = '适配设备类型, 1,移动, 2:电脑';
            });
        }
        if (!Schema::hasTable('product_logs')){
            Schema::create('product_logs', function(Blueprint $table) {
                $table->increments('id');
                $table->text('info');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('brands')){
            Schema::create('brands', function(Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->string('description');
                $table->string('logo');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('shop_categories')){
            Schema::create('shop_categories', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('pid');
                $table->integer('pos');
                $table->string('name');
                $table->string('description');
                $table->string('image');
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('skus')){
            Schema::create('skus', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->default(0);
                $table->string('product_name', 256)->default('')->comment = '商品名称';
                $table->string('sn', 100)->default('')->comment = '编号';
                $table->string('barcode', 100)->default('')->comment = '商品条码';
                $table->integer('quantity')->unsigned()->default(0)->comment = '库存量';
                $table->decimal('price', 8, 2)->default(0.00)->comment = '零售价';
                $table->decimal('prime_price', 8, 2)->default(0.00)->comment = '成本价';
                $table->string('attribute_values')->default('0')->comment = '1,2,3';
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('sku_attributes')){
            Schema::create('sku_attributes', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->default(0);
                $table->integer('attribute_value_id')->default(0);
                $table->integer('attribute_id')->default(0);
                $table->softDeletes();
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('shop_attributes')){
            Schema::create('shop_attributes', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('pid')->default(0);
                $table->integer('shop_category_id')->default(0);
                $table->string('name');
                $table->string('show_name');
                $table->tinyInteger('required')->default(0);
                $table->tinyInteger('is_base')->default(0);
                $table->tinyInteger('is_sell')->default(0)->comment = '销售属性';
                $table->tinyInteger('is_optional')->default(0);
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('pos')->default(0);
                $table->timestamps();
            });
        }
        if (!Schema::hasTable('shop_attribute_values')){
            Schema::create('shop_attribute_values', function(Blueprint $table) {
                $table->increments('id');
                $table->integer('shop_attribute_id')->default(0);
                $table->string('value');
                $table->tinyInteger('status')->default(0);
                $table->tinyInteger('pos')->default(0);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        if (Schema::hasTable('merchants')){Schema::drop('merchants');}
        if (Schema::hasTable('zones')){Schema::drop('zones');}
        if (Schema::hasTable('addresses')){Schema::drop('addresses');}
        if (Schema::hasTable('coupons')){Schema::drop('coupons');}
        if (Schema::hasTable('paid_logs')){Schema::drop('paid_logs');}
        if (Schema::hasTable('order_status')){Schema::drop('order_status');}
        if (Schema::hasTable('orders')){Schema::drop('orders');}
        if (Schema::hasTable('order_skus')){Schema::drop('order_skus');}
        if (Schema::hasTable('order_logs')){Schema::drop('order_logs');}
        if (Schema::hasTable('carts')){Schema::drop('carts');}
        if (Schema::hasTable('products')){Schema::drop('products');}
        if (Schema::hasTable('product_images')){Schema::drop('product_images');}
        if (Schema::hasTable('product_logs')){Schema::drop('product_logs');}
        if (Schema::hasTable('brands')){Schema::drop('brands');}
        if (Schema::hasTable('shop_categories')){Schema::drop('shop_categories');}
        if (Schema::hasTable('skus')){Schema::drop('skus');}
        if (Schema::hasTable('sku_attributes')){Schema::drop('sku_attributes');}
        if (Schema::hasTable('shop_attributes')){Schema::drop('shop_attributes');}
        if (Schema::hasTable('shop_attribute_values')){Schema::drop('shop_attribute_values');}
    }
}
