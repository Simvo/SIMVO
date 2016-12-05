<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMinorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('degree_id');
            $table->integer('program_id');
            $table->string('minor_name');
            $table->integer('minor_credits');
            $table->integer('version_id');
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
        Schema::drop('minors');
    }
}
