<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAffiliatesParameters extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Settings', function (Blueprint $table) {
            $table->string('id');
            $table->integer('affiliateId')->unsigned();
            $table->text('value');
            $table->timestamps();
        });

        Schema::table('Settings', function (Blueprint $table) {
            $table->primary('id');
        });

        Schema::table('Settings', function (Blueprint $table) {
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
        Schema::dropIfExists('Settings');
    }
}
