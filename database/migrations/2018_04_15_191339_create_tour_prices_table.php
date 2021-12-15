<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTourPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('TourPrices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tourId')->unsigned();
            $table->string('name');
            $table->float('adultPrice',8,2);
            $table->float('childrenPrice',8,2);
            $table->string('weekDays');
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::table('TourPrices', function (Blueprint $table) {
            $table->foreign('tourId')
            ->references('id')
            ->on('Tours')
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
        Schema::dropIfExists('TourPrices');
    }
}
