<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //$table->string('username')->unique();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('number')->nullable();
            $table->boolean('is_active')->default(1);
            $table->boolean('is_admin')->default(0);
            $table->boolean('is_reseller');
            $table->decimal('balance')->default(0.0);
            $table->decimal('referral_balance')->default(0.0);
            $table->string('profile')->nullable();
            $table->string('email')->unique();
            $table->bigInteger('points')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('api_token')->nullable();
            $table->integer('rate_limit')->default(60);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}