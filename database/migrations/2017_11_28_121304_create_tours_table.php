<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateToursTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Tours', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('path')->unique();
            $table->text('description')->nullable();
            $table->text('shortDescription')->nullable();
            $table->text('inclusions')->nullable();
            $table->text('exclusions')->nullable();
            $table->text('regulations')->nullable();
            $table->text('recommendations')->nullable();
            $table->text('policies')->nullable();
            $table->string('duration')->nullable();
            $table->text('itinerary')->nullable();
            $table->text('address')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('referenceId');
            $table->integer('tourProviderId');
            $table->integer('cityId');
            $table->integer('childrenMinAge');
            $table->integer('childrenMaxAge');
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
        Schema::dropIfExists('Tours');
    }
}
