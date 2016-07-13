<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDegreesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('degrees', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('user_id');
          $table->string('faculty');
          $table->integer('program_id');
          $table->integer('version_id');
          $table->string('enteringSemester');
          $table->integer('stream_version');
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
        Schema::drop('degrees');
    }
}
