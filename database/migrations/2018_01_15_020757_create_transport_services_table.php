<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransportServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TransportServices', function (Blueprint $table) {
            $table->increments('id');
            $table->float('onewayPrice',8,2);
            $table->float('roundtripPrice',8,2);
            $table->text('description')->nullable();
            $table->integer('destinationId');
            $table->integer('transportServiceTypeId')->unsigned();
            $table->timestamps();
        });

        Schema::table('TransportServices', function (Blueprint $table) {
            $table->foreign('transportServiceTypeId')
            ->references('id')
            ->on('TransportServiceTypes')
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
        Schema::dropIfExists('TransportServices');
    }
}
