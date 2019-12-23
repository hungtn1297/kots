<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDangerousStreetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dangerous_streets', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('startLongitude');
            $table->string('startLatitude');
            $table->string('endLongitude');
            $table->string('endLatitude');
            $table->string('description');
            $table->timestamp('expiredDate')->default(Carbon::now()->addDay(7));
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
        Schema::dropIfExists('dangerous_streets');
    }
}
