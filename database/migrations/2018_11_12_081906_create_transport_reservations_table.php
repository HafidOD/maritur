<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TransportReservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservationId')->unsigned();
            $table->datetime('arrivalDatetime');
            $table->string('arrivalFlight');
            $table->datetime('departureDatetime')->nullable();
            $table->string('departureFlight')->nullable();
            $table->integer('pax');
            $table->integer('hotelId')->nullable();
            $table->integer('destinationId')->nullable();
            $table->integer('tourId')->nullable();
            $table->integer('transportServiceTypeId')->unsigned();
            $table->float('subtotal',8,2);
            $table->float('total',8,2);
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('triptype')->default(1);
            $table->timestamps();
        });
        Schema::table('TransportReservations', function (Blueprint $table) {
            $table->foreign('reservationId')
            ->references('id')
            ->on('Reservations')
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
        Schema::dropIfExists('TransportReservations');
    }
}
