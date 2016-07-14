<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchedulesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
      Schema::create('schedules', function (Blueprint $table) {
          $table->increments('id');
          $table->string('SUBJECT_CODE');
          $table->string('COURSE_NUMBER');
          $table->integer('user_id');
          $table->integer('degree_id');
          $table->string('semester');
          $table->string('status');
          $table->string('grade');
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
      Schema::drop('schedules');
  }
}
