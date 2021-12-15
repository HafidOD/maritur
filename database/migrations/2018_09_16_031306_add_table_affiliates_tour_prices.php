<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableAffiliatesTourPrices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('AffiliatesTourPrices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('affiliateId')->unsigned();
            $table->integer('tourPriceId')->unsigned();
            $table->float('adultPrice',8,2);
            $table->float('childrenPrice',8,2);
        });
        Schema::table('AffiliatesTourPrices', function (Blueprint $table) {
            $table->foreign('tourPriceId')
            ->references('id')
            ->on('TourPrices')
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
        Schema::dropIfExists('AffiliatesTourPrices');
    }
}
