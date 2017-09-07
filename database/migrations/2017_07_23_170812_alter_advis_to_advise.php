<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAdvisToAdvise_20170723170812 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_advises', function (Blueprint $table) {
            $table->renameColumn('advis1', 'advise1');
            $table->renameColumn('advis2', 'advise2');
            $table->renameColumn('advis3', 'advise3');
            $table->renameColumn('advis4', 'advise4');
            $table->renameColumn('advis5', 'advise5');
            //
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
