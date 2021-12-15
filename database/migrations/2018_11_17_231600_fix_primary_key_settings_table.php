<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPrimaryKeySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('Settings', function (Blueprint $table) {
            $table->string('key');
        });
        DB::table('Settings')->update([
            'key' => DB::raw("id"),
        ]);
        Schema::table('Settings', function (Blueprint $table) {
            $table->dropColumn('id');
        });
        Schema::table('Settings', function (Blueprint $table) {
            $table->increments('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('Settings', function (Blueprint $table) {
            $table->dropPrimary('id');
            $table->dropColumn('id');
        });
        Schema::table('Settings', function (Blueprint $table) {
            $table->string('id');
        });
        DB::table('Settings')->update([
            'id' => DB::raw("key"),
        ]);
        Schema::table('Settings', function (Blueprint $table) {
            $table->dropColumn('key');
        });
    }
}
