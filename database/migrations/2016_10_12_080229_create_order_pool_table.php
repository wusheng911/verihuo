<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPoolTable_20161012080229 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_pool', function (Blueprint $table) {
            $table->integer('id');
            $table->string('sn', 4);
            $table->tinyInteger('used')->default(0)->comment = '1为已经使用, 0为未使用';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('order_pool');
    }
}
