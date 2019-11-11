<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('case_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('caseId')->unsigned();
            $table->string('knightId');
            $table->integer('status')->nullable();
            $table->timestamps();
            $table->foreign('caseId')->references('id')->on('cases');
            $table->foreign('knightId')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('case_details');
    }
}
