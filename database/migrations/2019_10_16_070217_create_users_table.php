<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->string('id',12)->primary();
            $table->string('name')->nullable();
            $table->text('address')->nullable();
            $table->integer('status');
            $table->string('gender')->nullable();
            $table->date('dateOfBirth')->nullable();
            $table->string('token');
            $table->integer('isDisable')->default(0);
            $table->integer('role');
            $table->string('image')->default('images/default-avatar.png');
            $table->integer('isFirstLogin')->default(1);
            $table->bigInteger('team_id')->unsigned()->nullable();
            $table->integer('isLeader')->default(0);
            $table->string('password')->nullable();
            $table->timestamps();
            // $table->foreign('team_id')->references('id')->on('knight_teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
