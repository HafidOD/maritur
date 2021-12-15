<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTableInactiveTours extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('InactiveTours', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('affiliateId')->unsigned();
            $table->integer('tourId')->unsigned();
        });
        Schema::table('InactiveTours', function (Blueprint $table) {
            $table->foreign('tourId')
            ->references('id')
            ->on('Tours')
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
        Schema::dropIfExists('InactiveTours');
    }
}
