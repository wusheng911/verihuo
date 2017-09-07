<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterInvoiceTableModUnique_20161227204642 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropUnique("invoice_title_type");
            $table->unique(['customer_id', 'invoice_title_type', 'content']);
        });
        Schema::table('search_keys', function (Blueprint $table) {
            $table->dropUnique("content_type");
            $table->unique(['customer_id', 'content_type', 'content']);
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
