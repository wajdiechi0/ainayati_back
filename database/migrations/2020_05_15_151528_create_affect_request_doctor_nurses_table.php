<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAffectRequestDoctorNursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('affect_request_doctor_nurses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('id_doctor')->unsigned();
            $table->foreign('id_doctor')->references('id')->on('users');
            $table->bigInteger('id_nurse')->unsigned();
            $table->foreign('id_nurse')->references('id')->on('users');
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
        Schema::dropIfExists('affect_request_doctor_nurses');
    }
}
