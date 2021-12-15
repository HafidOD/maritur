<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Reservations', function (Blueprint $table) {
            $table->increments('id');
            $table->string('refCode')->default('');
            
            $table->date('arrival');
            $table->date('departure');

            $table->string('clientFirstName');
            $table->string('clientLastName');
            $table->string('clientEmail');
            $table->string('clientPhone');
            $table->text('clientAddress');
            $table->integer('clientCountryId');
            $table->string('clientState');
            $table->string('clientCity');
            $table->string('clientZipcode');

            $table->string('specialRequests')->nullable();

            $table->string('currencyCode');
            $table->float('subtotal',8,2)->default(0);
            $table->float('total',8,2)->default(0);
            $table->string('exchangeRate')->default('');

            $table->longText('jsonRefQuote');
            $table->integer('refHotelId');
            $table->string('refCurrencyCode')->default('');
            $table->float('refSubtotal',8,2)->default(0);
            $table->float('refTotal',8,2)->default(0);
            $table->float('markup',8,2)->default(0);

            $table->string('token');
            $table->string('paymentToken');
            $table->string('paymentReferenceCode')->nullable();
            $table->tinyInteger('status');
            $table->tinyInteger('paymentMethod');
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
        Schema::dropIfExists('Reservations');
    }
}
