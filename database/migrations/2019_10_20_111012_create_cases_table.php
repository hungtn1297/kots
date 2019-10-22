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
            $table->string('knightConfirmId')->nullable();
            $table->string('knightCloseId')->nullable();
            $table->string('startLongitude');
            $table->string('startLatitude');
            $table->string('endLongitude')->nullable();
            $table->string('endLatitude')->nullable();
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
        Schema::dropIfExists('cases');
    }
}
