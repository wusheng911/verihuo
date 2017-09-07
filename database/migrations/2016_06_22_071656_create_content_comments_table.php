<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentCommentsTable_20160622071656 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pid')->nullable();
            $table->integer('user_id');
            $table->integer('content_id');
            $table->text('body');
            $table->integer('votes')->nullable();
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
        Schema::drop('content_comments');
    }
}
