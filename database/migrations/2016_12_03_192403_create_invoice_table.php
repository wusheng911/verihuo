<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceTable_20161203192403 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');            
            $table->integer('customer_id')->unsigned();
            $table->tinyInteger('invoice_title_type')->default(1)->comment = '1:个人, 2:公司';
            $table->string('content')->default("");
            $table->tinyInteger('is_default')->default(0);            
            $table->timestamps();
            $table->unique('customer_id', 'invoice_title_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invoices');
    }
}
