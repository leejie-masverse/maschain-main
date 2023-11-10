<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTaxonomyTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('taxonomy_vocabularies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique('name');
        });

        Schema::create('taxonomy_terms', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vocabulary_id')->unsigned();
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('data')->nullable();
            $table->integer('weight')->default(10);
            $table->enum('status', ['enabled', 'disabled'])->default('enabled');
            $table->timestamps();
            $table->softDeletes();


            $table->index('name');
            $table->index('weight');
            $table->index('status');
            $table->foreign('vocabulary_id')->references('id')->on('taxonomy_vocabularies')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('taxonomy_terms');
        Schema::dropIfExists('taxonomy_vocabularies');
    }
}
