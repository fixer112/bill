<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDndAndNondndToSmsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_histories', function (Blueprint $table) {
            $table->longText('dnd_numbers');
            $table->longText('nondnd_numbers');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sms_histories', function (Blueprint $table) {
            $table->dropColumn('dnd_numbers');
            $table->dropColumn('nondnd_numbers');

        });
    }
}