<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTourReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TourReservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservationId')->unsigned();
            $table->date('day');
            $table->integer('tourPriceId')->unsigned();
            $table->integer('adults');
            $table->integer('children');

            $table->float('adultPrice',8,2);
            $table->float('childrenPrice',8,2);
            $table->float('subtotal',8,2);
            $table->float('total',8,2);
            $table->integer('fromDestinationId')->default(-1);
            $table->timestamps();
        });
        Schema::table('TourReservations', function (Blueprint $table) {
            $table->foreign('reservationId')
            ->references('id')
            ->on('Reservations')
            ->onDelete('cascade');

            $table->foreign('tourPriceId')
            ->references('id')
            ->on('TourPrices');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('TourReservations');
    }
}
