<?php

use Diver\Field\Person;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFieldTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fields_person', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('entity');
            $table->string('field')->default('person');
            $table->string('title')->nullable();
            $table->string('username')->nullable();
            $table->string('full_name')->nullable();
            $table->string('family_name')->nullable();
            $table->string('given_name')->nullable();
            $table->enum('gender', [Person::GENDER_MALE, Person::GENDER_FEMALE])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('nationality')->nullable();
            $table->unsignedInteger('nationality_id')->nullable();
            $table->string('national_identity')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('full_name');
            $table->index('family_name');
            $table->index('given_name');
            $table->index('gender');
            $table->index('date_of_birth');
            $table->index('nationality');
            $table->index('national_identity');
        });

        Schema::create('fields_address', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('entity');
            $table->string('field')->default('person');
            $table->text('name')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('address')->nullable();
            $table->string('street1')->nullable();
            $table->string('street2')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('city')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->string('subdivision')->nullable();
            $table->unsignedInteger('subdivision_id')->nullable();
            $table->string('country')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table->text('google_formatted_address')->nullable();
            $table->text('google_search_address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('city');
            $table->index('country');
            $table->foreign('city_id')->references('id')->on('dataset_cities')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('subdivision_id')->references('id')->on('dataset_country_subdivisions')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('country_id')->references('id')->on('dataset_countries')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('fields_phone', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('entity');
            $table->string('field')->default('phone');
            $table->string('phone')->nullable();
            $table->string('country_code')->nullable();
            $table->string('number')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('phone');
            $table->index('country_code');
            $table->index('number');
            $table->foreign('country_id')->references('id')->on('dataset_countries')->onUpdate('cascade')->onDelete('set null');
        });

        Schema::create('fields_file', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('entity');
            $table->string('field')->default('file');
            $table->string('disk')->default(config('filesystems.default'));
            $table->string('path')->nullable();
            $table->string('dirname')->nullable();
            $table->string('filename')->nullable();
            $table->string('extension')->nullable();
            $table->integer('sorting')->nullable();
            $table->timestamps();

            $table->index('disk');
            $table->index('path');
            $table->index('dirname');
            $table->index('filename');
            $table->index('extension');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fields_phone');
        Schema::dropIfExists('fields_address');
        Schema::dropIfExists('fields_person');
    }
}
