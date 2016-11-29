<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customs', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id');
          $table->integer('degree_id');
          $table->string('title');
          $table->string('description');
          $table->string('focus');
          $table->string('semester');
          $table->integer('credits');
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
        Schema::drop('customs');
    }
}
