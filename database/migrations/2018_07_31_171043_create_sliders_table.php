<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSlidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('SliderImages', function (Blueprint $table) {
            $table->increments('id');
            $table->tinyInteger('section');
            $table->string('url')->default('/');
            $table->string('altText')->default('');
            $table->integer('orderx')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('SliderImages');
    }
}
