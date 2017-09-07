<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableRedirectUrls_20160824064955 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('redirect_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->string('old');
            $table->string('new');
            $table->char('code', 6);
            $table->string('is_actived');
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
        Schema::drop('redirect_urls');
    }
}
