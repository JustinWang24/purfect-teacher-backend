<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateAttendancesDetailsInWeekTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances_details', function (Blueprint $table) {
            $table->mediumInteger('week')->comment('当前学期的第几周');
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
            $table->dropColumn('week');
        });
    }
}
