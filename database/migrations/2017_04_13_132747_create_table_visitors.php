<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableVisitors_20170413132747 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->comment = '邀请用户名';
            $table->string('phone')->comment = '邀请用户手机';
            $table->string('code')->comment = '邀请码,唯一';
            $table->timestamp('entered_at')->nullable()->comment = '进入时间';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('visitors');
    }
}
