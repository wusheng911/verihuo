<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassScoreTables_20170701214541 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('classes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->nullable();
            $table->text('introduction')->nullable();
            $table->tinyInteger('status')->default(1)->comment = "1:启用, 0:关闭";
            $table->timestamps();
        });
        Schema::create('ccscores', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id');
            $table->integer('class_id');
            $table->timestamp('at');
            $table->double('c1')->nullable();
            $table->double('c2')->nullable();
            $table->double('c3')->nullable();
            $table->double('c4')->nullable();
            $table->double('c5')->nullable();
            $table->double('c6')->nullable();
            $table->double('c7')->nullable();
            $table->double('c8')->nullable();
            $table->double('c9')->nullable();
            $table->double('c10')->nullable();
            $table->double('a1')->nullable();
            $table->double('a2')->nullable();
            $table->double('a3')->nullable();
            $table->double('a4')->nullable();
            $table->double('a5')->nullable();
            $table->double('a6')->nullable();
            $table->double('a7')->nullable();
            $table->double('a8')->nullable();
            $table->double('a9')->nullable();
            $table->double('a10')->nullable();
            $table->text('advise1')->nullable();
            $table->text('advise2')->nullable();
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
        //
    }
}
