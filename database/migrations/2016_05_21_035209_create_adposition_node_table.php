<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdpositionNodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adposition_node', function (Blueprint $table) {
            $table->integer('adposition_id');
            $table->integer('node_id');
            
            $table->index('adposition_id');
            $table->index('node_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('adposition_node');
    }
}
