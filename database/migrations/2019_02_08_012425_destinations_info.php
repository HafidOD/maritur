<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DestinationsInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('DestinationsInfo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('destinationId')->unsigned();
            $table->integer('affiliateId')->unsigned();
            $table->text('description')->nullable();
        });
        Schema::table('DestinationsInfo', function (Blueprint $table) {

            $table->foreign('destinationId')
            ->references('id')
            ->on('Cities')
            ->onDelete('cascade');

            $table->foreign('affiliateId')
            ->references('id')
            ->on('Affiliates')
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
        Schema::dropIfExists('DestinationsInfo');
    }
}
