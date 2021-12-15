<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Reservations', function (Blueprint $table) {
            $table->text('paymentReference')->nullable();
            $table->tinyInteger('paymentStatus')->default(1); //pending
            $table->float('cancellationPenaltyTotal',8,2)->default(0);
            $table->dropColumn('jsonRefQuote');
            //
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
            $table->dropColumn('paymentReference');
            $table->dropColumn('paymentStatus');
            $table->dropColumn('cancellationPenaltyTotal');
            $table->longText('jsonRefQuote');
            //
        });
    }
}
