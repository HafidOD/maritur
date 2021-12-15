<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Hotels', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('path')->unique();
            $table->integer('code')->unique();
            $table->integer('cityCode')->nullable();
            $table->integer('stateCode')->nullable();
            $table->string('zoneCode')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Hotels');
    }
}
