<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount', 13, 2);
            $table->string('type')->default('debit');
            $table->string('user_id')->nullable();
            $table->string('desc');
            $table->string('reason')->default('top-up');
            $table->boolean('is_online')->default(1);
            $table->string('ref')->unique();
            $table->decimal('balance', 13, 2);

            //$table->boolean('active')->default(1);

            //$table->string('payment_id')->nullable();
            //$table->string('status')->default('active');

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
        Schema::dropIfExists('transactions');
    }
}