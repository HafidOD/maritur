<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateRoomReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('RoomsReservations', function (Blueprint $table) {
            $table->string('refCode')->default('');
            $table->float('cancellationPenaltyTotal',8,2)->default(0);
            $table->tinyInteger('status');
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
        Schema::table('RoomsReservations', function (Blueprint $table) {
            $table->dropColumn('refCode');
            $table->dropColumn('cancellationPenaltyTotal');
            $table->dropColumn('status');
            //
        });
    }
}
