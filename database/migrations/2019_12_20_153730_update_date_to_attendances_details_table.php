<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDateToAttendancesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('attendances_details', function (Blueprint $table) {
            $table->dropColumn('date');
        });
        Schema::table('attendances_details', function (Blueprint $table) {
            $table->dateTime('date')->comment('课程的当天时间')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances_details', function (Blueprint $table) {
            $table->dropColumn('date');
        });
        Schema::table('attendances_details', function (Blueprint $table) {
            //
            $table->dateTime('date')->comment('课程的当天时间')->nullable();
        });
    }
}
