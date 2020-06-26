<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyGroupIdToSmsGroupIdInSmsHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sms_histories', function (Blueprint $table) {
            $table->dropColumn('group_id');
            $table->string('sms_group_id')->nullable();

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
            $table->string('group_id')->nullable();
            $table->dropColumn('sms_group_id');

        });
    }
}