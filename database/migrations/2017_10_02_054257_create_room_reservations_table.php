<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('RoomsReservations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('reservationId');

            $table->integer('refRoomTypeId');
            $table->string('refRoomTypeName');
            $table->integer('refRatePlanId');
            $table->string('refRatePlanName');
            $table->integer('adults');
            $table->integer('children');
            $table->text('jsonAges');
            $table->text('jsonRefExtraData');

            $table->float('subtotal',8,2);
            $table->float('total',8,2);

            $table->float('refSubtotal',8,2);
            $table->float('refTotal',8,2);
            $table->float('markup',8,2);
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
        Schema::dropIfExists('RoomsReservations');
    }
}
