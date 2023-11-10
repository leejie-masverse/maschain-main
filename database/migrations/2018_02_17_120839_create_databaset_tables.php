<?php

use Diver\Dataset\CountrySubdivision;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDatabasetTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dataset_countries', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('iso_code_alpha2')->nullable();
            $table->string('iso_code_alpha3')->nullable();
            $table->string('calling_code');
            $table->string('capital_city')->nullable();
            $table->unsignedInteger('capital_city_id')->nullable();
            $table->string('address_format');

            $table->unique('name');
            $table->unique('iso_code_alpha2');
            $table->unique('iso_code_alpha3');
            $table->unique('calling_code');
            $table->index('capital_city');
        });

        Schema::create('dataset_country_subdivisions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('iso_code')->nullable();
            $table->string('category')->nullable();
            $table->string('capital_city')->nullable();
            $table->unsignedInteger('capital_city_id')->nullable();
            $table->unsignedInteger('country_id');

            $table->index('name');
            $table->unique('iso_code');
            $table->index('category');
            $table->index('capital_city');
            $table->foreign('country_id')->references('id')->on('dataset_countries')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::create('dataset_cities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('subdivision_id');
            $table->unsignedInteger('country_id');

            $table->index('name');
            $table->foreign('subdivision_id')->references('id')->on('dataset_country_subdivisions')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('dataset_countries')->onUpdate('cascade')->onDelete('cascade');
        });

        Schema::table('dataset_country_subdivisions', function (Blueprint $table) {
            $table->foreign('capital_city_id')->references('id')->on('dataset_cities')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::table('dataset_countries', function (Blueprint $table) {
            $table->foreign('capital_city_id')->references('id')->on('dataset_cities')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('dataset_cities');
        Schema::dropIfExists('dataset_country_subdivisions');
        Schema::dropIfExists('dataset_countries');

        Schema::enableForeignKeyConstraints();
    }
}
