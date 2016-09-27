<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowchartErrorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flowchart_errors', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id');
          $table->integer('schedule_id');
          $table->string('message');
          $table->string('dependencies');
          $table->string('type');
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
        Schema::drop('flowchart_errors');
    }
}
