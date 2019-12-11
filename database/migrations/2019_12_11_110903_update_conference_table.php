<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateConferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dateTime('from')->comment('会议开始时间')->change();
            $table->dateTime('to')->comment('会议结束时间')->change();
            //
        });

        Schema::table('conferences_users', function (Blueprint $table) {
            $table->dropColumn('date');
            $table->dateTime('from')->comment('会议开始时间')->change();
            $table->dateTime('to')->comment('会议结束时间')->change();
            //
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('conferences', function (Blueprint $table) {
            $table->dateTime('date')->comment('会议当天时间')->nullable();
            $table->time('from')->comment('会议开始时间')->change();
            $table->time('to')->comment('会议结束时间')->nullable()->change();
        });

        Schema::table('conferences_users', function (Blueprint $table) {
            $table->dateTime('date')->comment('会议当天时间')->nullable();
            $table->time('from')->comment('会议开始时间')->change();
            $table->time('to')->comment('会议结束时间')->nullable()->change();
        });
    }
}
