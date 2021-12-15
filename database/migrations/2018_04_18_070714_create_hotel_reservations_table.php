<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotelReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('HotelReservations', function (Blueprint $table) {
            $table->increments('id');
            // codigo referencia API
            $table->string('refCode')->default('');
            $table->integer('reservationId')->unsigned();
            
            $table->date('arrival');
            $table->date('departure');

            $table->float('subtotal',8,2)->default(0);
            $table->float('total',8,2)->default(0);
            $table->float('exchangeRate')->default(1);

            $table->integer('hotelId')->unsigned();
            // montos en modena cotizada API
            $table->string('refCurrencyCode')->default('');
            $table->float('refSubtotal',8,2)->default(0);
            $table->float('refTotal',8,2)->default(0);
            $table->float('markup',8,2)->default(0);

            $table->tinyInteger('status');
            $table->timestamps();
        });

        Schema::table('HotelReservations', function (Blueprint $table) {
            $table->foreign('hotelId')
            ->references('id')
            ->on('Hotels');

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
        Schema::dropIfExists('HotelReservations');
    }
}
