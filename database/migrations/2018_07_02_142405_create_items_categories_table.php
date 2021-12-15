<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemsCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ItemLists', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('orderx')->default(1);
            $table->tinyInteger('itemType')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
        Schema::create('ItemListRelations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('referenceId');
            $table->float('fromPrice',8,2);
            $table->string('currencyCode');
            $table->integer('itemListId')->unsigned();
            $table->timestamps();
        });
        Schema::table('Hotels', function (Blueprint $table) {
            $table->integer('stars')->default(0);
        });
        Schema::table('ItemListRelations', function (Blueprint $table) {
            $table->foreign('itemListId')
            ->references('id')
            ->on('ItemLists')
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
        Schema::dropIfExists('ItemListRelations');
        Schema::dropIfExists('ItemLists');
        Schema::table('Hotels', function (Blueprint $table) {
            $table->dropColumn('stars');
        });
    }
}
