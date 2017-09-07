<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableModFschools_20170730213853 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('final_reports', function (Blueprint $table) {
            //
            $table->string('recommend_school1')->nullable();
            $table->string('recommend_school2')->nullable();
            $table->string('recommend_school3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('final_reports', function (Blueprint $table) {
            //
        });
    }
}
