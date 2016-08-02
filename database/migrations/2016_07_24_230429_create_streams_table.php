<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('streams', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('structure_id');
          $table->string('semester_index');
          $table->string('term');
          $table->string('course');
          $table->string('status');
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
        Schema::drop('streams');
    }
}
