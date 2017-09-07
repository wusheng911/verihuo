<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('content_category_id'); 
            $table->char('name');
            $table->char('title');
            $table->char('keywords');
            $table->text('description');
            $table->text('content');
            $table->char('seo_title');
            $table->char('seo_keywords');
            $table->char('seo_description');
            $table->integer('votes');
            $table->smallInteger('type');
            $table->smallInteger('status');
            $table->integer('image');
            $table->integer('created_by');
            $table->integer('modified_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contents');
    }
}
