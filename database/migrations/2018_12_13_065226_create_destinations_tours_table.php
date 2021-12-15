<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinationsToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DestinationsTours', function (Blueprint $table) {
            $table->integer('tourId')->unsigned();
            $table->integer('destinationId')->unsigned();
        });
        Schema::table('DestinationsTours', function (Blueprint $table) {
            $table->primary(['tourId', 'destinationId']);

            $table->foreign('tourId')
            ->references('id')
            ->on('Tours')
            ->onDelete('cascade');

            $table->foreign('destinationId')
            ->references('id')
            ->on('Cities')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('DestinationsTours');
    }
}
