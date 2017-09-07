<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAiteidToCustomersTable_20160713090745 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('aite_id', 255)->nullable();
            $table->string('portrait', 255)->nullable()->change();
            $table->unique('aite_id', 'aite_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('aite_id');
            $table->string('portrait', 255)->change();
            $talbe->dropUnique('aite_id_unique');
        });
    }
}
