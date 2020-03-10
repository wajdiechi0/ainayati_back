<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->bigIncrements('id');
            $table->string('name')->default('');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('birthdate')->default('');
            $table->string('home_address')->default('');
            $table->string('work_address')->default('');
            $table->string('specialty')->default('');
            $table->string('description')->default('');
            $table->string('weight')->default(0);
            $table->string('height')->default(0);
            $table->string('gender')->default('');
            $table->rememberToken();
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
