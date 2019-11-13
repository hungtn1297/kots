<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKnightTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('knight_teams', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('leaderId');
            $table->string('name');
            $table->string('address');
            $table->timestamps();
            // $table->foreign('leaderId')->references('id')->on('users')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('knight_teams');
    }
}
