<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Houses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('path')->unique();
            $table->text('description')->nullable();
            $table->text('shortDescription')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('destinationId');
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
        Schema::dropIfExists('Houses');
    }
}
