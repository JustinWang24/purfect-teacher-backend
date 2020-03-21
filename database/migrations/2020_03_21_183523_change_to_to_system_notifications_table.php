<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeToToSystemNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            //
            $table->bigInteger('to')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('system_notifications', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('to')->change();
        });
    }
}
