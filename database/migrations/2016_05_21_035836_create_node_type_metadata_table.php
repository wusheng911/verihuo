<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeTypeMetadataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('node_type_metadata', function (Blueprint $table) {
            $table->integer('node_type_id');
            $table->integer('seq_no')->default(1);
            $table->integer('node_attr_id');
            $table->enum('cardinality', ['one','many']);
            $table->enum('editable', ['default','readonly','hidden']);
            $table->timestamps();
            
            $table->index(['node_type_id', 'node_attr_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('node_type_metadata');
    }
}
