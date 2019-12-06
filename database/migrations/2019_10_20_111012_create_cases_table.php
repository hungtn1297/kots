<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('status');
            $table->string('citizenId');
            $table->text('message');
            $table->integer('type');
            $table->string('image')->nullable();
            $table->string('sound')->nullable();
            $table->string('knightConfirmId')->nullable();
            $table->string('knightCloseId')->nullable();
            $table->string('startLongitude');
            $table->string('startLatitude');
            $table->string('endLongitude')->nullable();
            $table->string('endLatitude')->nullable();
            $table->integer('rate')->nullable();
            $table->text('notice')->nullable();
            $table->string('key')->nullable();
            $table->timestamps();
            // $table->foreign('citizenId')->references('id')->on('users');
            // $table->foreign('knightConfirmId')->references('id')->on('users');
            // $table->foreign('knightCloseId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cases');
    }
}
