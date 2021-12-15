<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReferenceColumnsImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('Images', function (Blueprint $table) {
            $table->integer('referenceId')->default(0);
            $table->tinyInteger('referenceType')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Images', function (Blueprint $table) {
            $table->dropColumn('referenceId');
            $table->dropColumn('referenceType');
        });
    }
}
