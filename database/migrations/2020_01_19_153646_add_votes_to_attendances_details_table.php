<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVotesToAttendancesDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances_details', function (Blueprint $table) {
            //
            $table->dropColumn('date');
            $table->string('remark')->comment('备注')->nullable();
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
            //
            $table->dateTime('date')->nullable();
            $table->dropColumn('remark');
        });
    }
}
