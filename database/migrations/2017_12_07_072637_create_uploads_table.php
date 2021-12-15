<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Uploads', function (Blueprint $table) {
            $table->increments('id');
            $table->string('filename');
            $table->string('folder',10);
            $table->integer('referenceId');
            $table->integer('size');
            $table->string('type',40);
            $table->string('extension',10);
            $table->integer('orderx');
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
        Schema::dropIfExists('Uploads');
    }
}
