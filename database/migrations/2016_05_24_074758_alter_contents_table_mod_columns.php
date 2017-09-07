<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterContentsTableModColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('contents', function (Blueprint $table) {
            $table->string('content_category_id')->nullable()->change();
            $table->string('name')->nullable()->change();
            $table->string('title')->nullable()->change();
            $table->string('keywords')->nullable()->change();
            $table->string('description')->nullable()->change();
            $table->text('content')->nullable()->change();
            $table->string('seo_title')->nullable()->change();
            $table->string('seo_keywords')->nullable()->change();
            $table->string('seo_description')->nullable()->change();
            $table->integer('votes')->nullable()->change();
            $table->smallInteger('type')->nullable()->change();
            $table->smallInteger('status')->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->integer('created_by')->nullable()->change();
            $table->integer('modified_by')->nullable()->change();

            $table->renameColumn('name', 'subtitle');
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
        Schema::table('contents', function (Blueprint $table) {
            //
        });
    }
}
