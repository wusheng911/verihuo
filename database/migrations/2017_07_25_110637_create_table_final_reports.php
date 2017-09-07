<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableFinalReports_20170725110637 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->nullable();
            $table->string('professor')->nullable();
            $table->double('final')->nullable();
            $table->double('gpa_grade')->nullable();
            $table->double('test_score')->nullable();
            $table->double('course')->nullable();
            $table->double('award')->nullable();
            $table->double('activity')->nullable();
            $table->double('work_exp')->nullable();
            $table->double('research_exp')->nullable();
            $table->integer('fschool1')->nullable();
            $table->double('fschool1_percent')->nullable();
            $table->integer('fschool2')->nullable();
            $table->double('fschool2_percent')->nullable();
            $table->integer('fschool3')->nullable();
            $table->double('fschool3_percent')->nullable();
            $table->integer('fschool4')->nullable();
            $table->double('fschool4_percent')->nullable();
            $table->integer('fschool5')->nullable();
            $table->double('fschool5_percent')->nullable();
            $table->string('tip1')->nullable();
            $table->string('tip2')->nullable();
            $table->string('tip3')->nullable();
            $table->integer('recommend1_school_id')->nullable();
            $table->integer('recommend2_school_id')->nullable();
            $table->integer('recommend3_school_id')->nullable();
            $table->text('action_plan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::drop('final_reports');
    }
}
