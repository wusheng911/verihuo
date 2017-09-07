<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOauthTable_20160713090758 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oauth', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->char('channel', 20);
            //client_id:表示客户端的ID
            $table->string('client_id', 255)->nullable();
            //openid是此网站上唯一对应用户身份的标识
            $table->string('open_id', 255)->nullable();
            $table->string('info', 255)->nullable();
            $table->timestamps();
            
            $table->index(['channel', 'open_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('oauth');
    }
}
