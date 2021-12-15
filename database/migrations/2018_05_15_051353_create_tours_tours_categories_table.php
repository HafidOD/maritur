<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToursToursCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ToursTourCategories', function (Blueprint $table) {
            $table->integer('tourId')->unsigned();
            $table->integer('tourCategoryId')->unsigned();
        });
        Schema::table('ToursTourCategories', function (Blueprint $table) {
            $table->primary(['tourId', 'tourCategoryId']);

            $table->foreign('tourId')
            ->references('id')
            ->on('Tours')
            ->onDelete('cascade');

            $table->foreign('tourCategoryId')
            ->references('id')
            ->on('TourCategories')
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
        Schema::dropIfExists('ToursTourCategories');
    }
}
