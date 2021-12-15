<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableAffiliatesTourPricesTransportation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AffiliatesTourPriceTrans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('affiliateId')->unsigned();
            $table->integer('tourPriceTransportationId')->unsigned();
            $table->float('adultPrice',8,2);
            $table->float('childrenPrice',8,2);
        });
        Schema::table('AffiliatesTourPriceTrans', function (Blueprint $table) {
            $table->foreign('tourPriceTransportationId')
            ->references('id')
            ->on('TourPriceTransportation')
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
        Schema::dropIfExists('AffiliatesTourPriceTrans');
    }
}
