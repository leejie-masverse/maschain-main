<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Src\People\User;

class CreatePeopleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->nullable();
            $table->string('affiliate_id')->nullable();
            $table->string('formatted_phone_number')->nullable();
            $table->string('status')->nullable()->default(User::STATUS_VERIFYING);
            $table->string('password');
            $table->string('phone_code')->nullable();
            $table->string('phone_number')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->json('metadata')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->unique('email');
            $table->index('status');
            $table->index('id');
        });

        Schema::create('password_resets', function (Blueprint $table) {
            $table->string('email')->index();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('bank_id');
            $table->string('account_number');
            $table->string('account_holder_name')->nullable();
            $table->string('identification_no')->nullable()->comment('IC or business registration number of account owner');
            $table->softDeletes();

            $table->timestamps();
        });

        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('phone_number');
            $table->string('code');

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
        Schema::dropIfExists('otp_verifications');
        Schema::dropIfExists('bank_accounts');
        Schema::dropIfExists('password_resets');
        Schema::dropIfExists('users');
    }
}
