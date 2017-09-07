<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerInfoTables_20170618212235 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_scores', function (Blueprint $table) {
            $table->integer('customer_id')->unique();
            $table->date('graduate_year')->nullable();
            $table->double('gpa')->nullable();
            $table->double('class_rank')->nullable();
            $table->double('ib_score')->nullable();
            $table->double('toefl_listening')->nullable();
            $table->double('toefl_speaking')->nullable();
            $table->double('toefl_reading')->nullable();
            $table->double('toefl_writting')->nullable();
            $table->double('ielts_listening')->nullable();
            $table->double('ielts_speaking')->nullable();
            $table->double('ielts_reading')->nullable();
            $table->double('ielts_writting')->nullable();
            $table->integer('sat_i_math')->nullable();
            $table->integer('sat_i_critical_reading')->nullable();
            $table->integer('sat_i_writing')->nullable();
            $table->string('sat_ii_1_name')->nullable();
            $table->integer('sat_ii_1_score')->nullable();
            $table->string('sat_ii_2_name')->nullable();
            $table->integer('sat_ii_2_score')->nullable();
            $table->string('sat_ii_3_name')->nullable();
            $table->integer('sat_ii_3_score')->nullable();
            $table->tinyInteger('act')->nullable();
        });
        schema::create('customer_exp1', function (blueprint $table) {
            $table->integer('customer_id')->unique();
            $table->string('current', 512)->nullable();
            $table->string('aphonors', 512)->nullable();
            $table->string('awards' ,512)->nullable();
            $table->string('first_language:', 255)->nullable();
            $table->string('family', 512)->nullable();
            $table->string('coursework', 512)->nullable();
        });

        Schema::create('customer_exp2', function (Blueprint $table) {
            $table->integer('customer_id')->unique();
            $table->string('won', 255)->nullable();
            $table->string('activite', 512)->nullable();
            $table->string('leadership', 512)->nullable();
            $table->string('arts', 512)->nullable();
            $table->string('community', 512)->nullable();
            $table->string('research', 512)->nullable();
            $table->string('work', 512)->nullable();
            $table->string('intemship', 512)->nullable();
            $table->string('summer', 512)->nullable();
        });

        Schema::create('customer_fschool', function (Blueprint $table) {
            $table->integer('customer_id')->nullable();
            $table->integer('school_id')->nullable();
            $table->integer('pos')->nullable();
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
