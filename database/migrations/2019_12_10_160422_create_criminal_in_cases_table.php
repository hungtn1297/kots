<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCriminalInCasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('criminal_in_cases', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('caseId');
            $table->unsignedBigInteger('criminalId');
            $table->timestamps();
            $table->foreign('caseId')->references('id')->on('cases');
            $table->foreign('criminalId')->references('id')->on('criminals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('criminal_in_cases');
    }
}
