<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCustomerVipinfoEtc_20170715191520 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //class info
        Schema::create('customer_info1', function (Blueprint $table) {

            $table->integer('customer_id');
            $table->tinyInteger('status')->nullable()->comment = "1:active, 2pending, 3:closed";
            $table->integer('course_id')->nullable()->comment
                = "1:study abroad, 2:business english, 3:kids global, 4:primary, 5:junior hight, 6:adults english";
            $table->timestamp('onboard')->nullable();
            $table->string('cid', 50)->nullable();
            $table->string('level')->nullable();
            $table->string('test_score')->nullable();
            $table->string('parents_name')->nullable();
            $table->string('parents_phone')->nullable();
            $table->string('points')->nullable();
            $table->text('contract')->nullable();
            $table->text('advisor')->nullable();
            $table->text('awards')->nullable();
            $table->timestamps();

        });

        Schema::table('teachers', function (Blueprint $table) {
            $table->tinyInteger('level')->nullable()->comment ="2:master";
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
