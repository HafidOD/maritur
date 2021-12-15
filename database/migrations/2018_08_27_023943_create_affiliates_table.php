<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAffiliatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Affiliates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->tinyInteger('status')->default(1);
            $table->string('domain');
            $table->boolean('isAdmin')->default(false);
            $table->timestamps();
        });
        DB::table('Affiliates')->insert(
            [
                'id' => 1,
                'name'=>'Goguytravel',
                'status'=>1,
                'domain'=>"goguytravel.com",
                'isAdmin'=>1,
            ]
        );
        Schema::table('ItemLists', function (Blueprint $table) {
            $table->integer('affiliateId')->unsigned();
        });
        DB::table('ItemLists')->update(['affiliateId' => 1]);
        Schema::table('ItemLists', function (Blueprint $table) {
            $table->foreign('affiliateId')
            ->references('id')
            ->on('Affiliates')
            ->onDelete('cascade');
        });

        Schema::table('Reservations', function (Blueprint $table) {
            $table->integer('affiliateId')->unsigned();
        });
        DB::table('Reservations')->update(['affiliateId' => 1]);
        Schema::table('Reservations', function (Blueprint $table) {
            $table->foreign('affiliateId')
            ->references('id')
            ->on('Affiliates')
            ->onDelete('cascade');
        });

        Schema::table('SliderImages', function (Blueprint $table) {
            $table->integer('affiliateId')->unsigned();
        });
        DB::table('SliderImages')->update(['affiliateId' => 1]);
        Schema::table('SliderImages', function (Blueprint $table) {
            $table->foreign('affiliateId')
            ->references('id')
            ->on('Affiliates')
            ->onDelete('cascade');
        });

        Schema::table('Tours', function (Blueprint $table) {
            $table->integer('affiliateId')->unsigned();
        });
        DB::table('Tours')->update(['affiliateId' => 1]);
        Schema::table('Tours', function (Blueprint $table) {
            $table->foreign('affiliateId')
            ->references('id')
            ->on('Affiliates')
            ->onDelete('cascade');
        });

        Schema::table('TransportServiceTypes', function (Blueprint $table) {
            $table->integer('affiliateId')->unsigned();
        });
        DB::table('TransportServiceTypes')->update(['affiliateId' => 1]);
        Schema::table('TransportServiceTypes', function (Blueprint $table) {
            $table->foreign('affiliateId')
            ->references('id')
            ->on('Affiliates')
            ->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('affiliateId')->unsigned();
        });
        DB::table('users')->update(['affiliateId' => 1]);
        Schema::table('users', function (Blueprint $table) {
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
        Schema::table('ItemLists', function (Blueprint $table) {
            $table->dropForeign('itemlists_affiliateid_foreign');
            $table->dropColumn('affiliateId');
        });
        Schema::table('Reservations', function (Blueprint $table) {
            $table->dropForeign('reservations_affiliateid_foreign');
            $table->dropColumn('affiliateId');
        });
        Schema::table('SliderImages', function (Blueprint $table) {
            $table->dropForeign('sliderimages_affiliateid_foreign');
            $table->dropColumn('affiliateId');
        });
        Schema::table('Tours', function (Blueprint $table) {
            $table->dropForeign('tours_affiliateid_foreign');
            $table->dropColumn('affiliateId');
        });
        Schema::table('TransportServiceTypes', function (Blueprint $table) {
            $table->dropForeign('transportservicetypes_affiliateid_foreign');
            $table->dropColumn('affiliateId');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign('users_affiliateid_foreign');
            $table->dropColumn('affiliateId');
        });
        Schema::dropIfExists('Affiliates');
    }
}
