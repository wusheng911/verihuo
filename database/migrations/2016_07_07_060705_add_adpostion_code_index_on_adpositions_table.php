<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdpostionCodeIndexOnAdpositionsTable_20160707060705 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adpositions', function (Blueprint $table) {
            $table->unique('adposition_code', 'adposition_code_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adpositions', function (Blueprint $table) {
            $table->dropUnique('adposition_code_unique');
        });
    }
}
