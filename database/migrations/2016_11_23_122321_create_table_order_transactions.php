<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableOrderTransactions_20161123122321 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->default(0);
            $table->unsignedInteger('payment_method_id')->nullable();
            $table->string('payment_trace_no')->nullable()->comment = '交易追踪号码';
            $table->decimal('total',8, 2)->default(0.00);
            $table->tinyInteger('status');
            $table->unsignedInteger('customer_id')->nullable()->comment = '来自客户操作';
            $table->unsignedInteger('user_id')->nullable()->comment = '来自后台管理员操作';
            $table->string('description')->nullable();
            $table->timestamps();
        });
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('pay_order_id')->after('paid_status')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_transactions');
    }
}
