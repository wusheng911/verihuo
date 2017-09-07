<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentCategoryTagTable_20160802072832 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_category_tag', function (Blueprint $table) {
            $table->integer('content_category_id')->unsigned();
            $table->integer('content_tag_id')->unsigned();
            $table->foreign('content_category_id')->references('id')->on('content_categories')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('content_tag_id')->references('id')->on('content_tags')
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('content_category_tag');
    }
}
