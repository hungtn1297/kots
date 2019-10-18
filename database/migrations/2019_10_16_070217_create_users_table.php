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
            $table->string('phone');
            $table->string('name');
            $table->text('address');
            $table->integer('status');
            $table->integer('isDisable')->default(0);
            $table->integer('role');
            $table->string('image')->default('images/default-avatar.png');
            $table->integer('team_id')->nullable();
            $table->string('password')->nullalbe();
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
        Schema::dropIfExists('users');
    }
}
