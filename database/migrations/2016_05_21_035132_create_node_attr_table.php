<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeAttrTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_attr', function (Blueprint $table) {
            $table->integer('node_id');
            $table->integer('node_attr_id');
            $table->integer('seq_no')->default(1);
            $table->text('value');
            $table->timestamps();
            
            $table->index(['node_id', 'node_attr_id', 'seq_no']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('node_attr');
    }
}
