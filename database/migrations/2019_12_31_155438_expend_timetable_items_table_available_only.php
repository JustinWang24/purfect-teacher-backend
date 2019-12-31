<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpendTimetableItemsTableAvailableOnly extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timetable_items', function (Blueprint $table) {
            $table->text('available_only')->nullable()->comment('如果只在特定时间有效, 则写在这里');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('timetable_items', function (Blueprint $table) {
            $table->dropColumn('available_only');
        });
    }
}
