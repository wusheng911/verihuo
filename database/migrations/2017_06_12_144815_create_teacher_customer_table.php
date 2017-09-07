<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherCustomerTable_20170612144815 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_customer', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('teacher_id')->nullable()->comment = '教师id';
            $table->integer('customer_id')->nullable()->comment = 'customer id';
            //$table->timestamp('entered_at')->nullable()->comment = '进入时间';
            //$table->timestamps();
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
