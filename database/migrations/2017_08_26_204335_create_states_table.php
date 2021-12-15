<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('States', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('path')->unique();
            $table->integer('code')->unique();
            $table->string('searchCode')->nullable();
            $table->string('searchZoneCode')->nullable();
            $table->integer('countryCode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('States');
    }
}
