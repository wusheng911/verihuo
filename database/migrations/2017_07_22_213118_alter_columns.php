<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumns_20170722213118 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customer_exp1', function (Blueprint $table) {
            $table->renameColumn('`first_language:`', 'first_language');
            //
        });

        Schema::table('customer_exp2', function (Blueprint $table) {
            $table->renameColumn('intemship', 'intership');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
