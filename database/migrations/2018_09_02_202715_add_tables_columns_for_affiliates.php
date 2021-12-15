<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTablesColumnsForAffiliates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('TourPrices', function (Blueprint $table) {
            $table->float('netAdultPrice',8,2);
            $table->float('netChildrenPrice',8,2);
        });
        DB::table('TourPrices')->update([
            'netAdultPrice' => DB::raw("adultPrice"),
            'netChildrenPrice' => DB::raw("childrenPrice"),
        ]);

        // fix TourPriceTransportation
        Schema::create('TourPriceTransportation2', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tourPriceId')->unsigned();
            $table->integer('destinationId')->unsigned();
            $table->float('adultPrice',8,2);
            $table->float('childrenPrice',8,2);
            $table->text('description')->nullable();
        });
        Schema::table('TourPriceTransportation2', function (Blueprint $table) {
            $table->foreign('tourPriceId')
            ->references('id')
            ->on('TourPrices')
            ->onDelete('cascade');

            $table->foreign('destinationId')
            ->references('id')
            ->on('Cities');
        });
        $tpts = DB::table('TourPriceTransportation')->get();
        $objs = [];
        foreach ($tpts as $tpt) {
            $objs[] = [
                'tourPriceId'=>$tpt->tourPriceId,
                'destinationId'=>$tpt->destinationId,
                'adultPrice'=>$tpt->adultPrice,
                'childrenPrice'=>$tpt->childrenPrice,
                'description'=>$tpt->description,
            ];
        }
        DB::table('TourPriceTransportation2')->insert($objs);
        Schema::dropIfExists('TourPriceTransportation');
        Schema::rename('TourPriceTransportation2', 'TourPriceTransportation');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('TourPrices', function (Blueprint $table) {
            $table->dropColumn('netAdultPrice');
            $table->dropColumn('netChildrenPrice');
        });
    }
}
