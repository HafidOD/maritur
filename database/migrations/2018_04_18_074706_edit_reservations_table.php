<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Reservations', function (Blueprint $table) {
            $table->dropColumn([
                'refCode',
                'arrival',
                'departure',
                'exchangeRate',
                'jsonRefQuote',
                'refHotelId',
                'refCurrencyCode',
                'refSubtotal',
                'refTotal',
                'markup',
            ]);
        });
        Schema::table('RoomsReservations', function (Blueprint $table) {
            $table->renameColumn('reservationId', 'hotelReservationId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Reservations', function (Blueprint $table) {
            $table->string('refCode');
            $table->date('arrival');
            $table->date('departure');
            $table->string('exchangeRate');
            $table->longText('jsonRefQuote');
            $table->integer('refHotelId');
            $table->string('refCurrencyCode');
            $table->float('refSubtotal',8,2);
            $table->float('refTotal',8,2);
            $table->float('markup',8,2);
        });
        Schema::table('RoomsReservations', function (Blueprint $table) {
            $table->renameColumn('hotelReservationId','reservationId');
        });
    }
}
