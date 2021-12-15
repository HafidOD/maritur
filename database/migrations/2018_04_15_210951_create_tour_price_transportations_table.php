<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTourPriceTransportationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TourPriceTransportation', function (Blueprint $table) {
            $table->integer('tourPriceId')->unsigned();
            $table->integer('destinationId')->unsigned();
            $table->float('adultPrice',8,2);
            $table->float('childrenPrice',8,2);
            $table->text('description')->nullable();
            $table->timestamps();
        });
        Schema::table('TourPriceTransportation', function (Blueprint $table) {
            $table->primary(['tourPriceId', 'destinationId']);

            $table->foreign('tourPriceId')
            ->references('id')
            ->on('TourPrices')
            ->onDelete('cascade');

            $table->foreign('destinationId')
            ->references('id')
            ->on('Cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TourPriceTransportation');
    }
}
