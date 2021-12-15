<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHolderToReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Reservations', function (Blueprint $table) {
            $table->string('holdername')->default('');
        });
        Schema::table('ItemLists', function (Blueprint $table) {
            $table->tinyInteger('section')->default(1);
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
            $table->dropColumn('holdername');
        });
        Schema::table('ItemLists', function (Blueprint $table) {
            $table->dropColumn('section');
        });
    }
}
