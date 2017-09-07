<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentContentTagTable_20160727034350 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_content_tag', function (Blueprint $table) {
            $table->integer('content_id')->unsigned();
            $table->integer('content_tag_id')->unsigned();
            $table->foreign('content_id')->references('id')->on('contents')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('content_tag_id')->references('id')->on('content_tags')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->primary(['content_id', 'content_tag_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content_content_tag');
    }
}
