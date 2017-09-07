<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContentCategoriesTableModColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('content_categories', function (Blueprint $table) {
            $table->integer('pid')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('description')->nullable()->change();
            $table->string('seo_title')->nullable()->change();
            $table->string('seo_keywords')->nullable()->change();
            $table->string('seo_description')->nullable()->change();

            $table->renameColumn('name', 'title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('content_categories', function (Blueprint $table) {
            //
        });
    }
}
