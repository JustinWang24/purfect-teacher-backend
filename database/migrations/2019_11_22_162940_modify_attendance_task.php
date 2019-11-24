<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAttendanceTask extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_tasks', function (Blueprint $table) {
            $table->dropColumn('campus_id');
        });
        Schema::table('attendance_schedules', function (Blueprint $table) {
            $table->dropColumn('campus_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_tasks', function (Blueprint $table) {
            $table->unsignedInteger('campus_id')->comment('校区ID');
        });
        Schema::table('attendance_schedules', function (Blueprint $table) {
            $table->unsignedInteger('campus_id')->comment('校区ID');
        });
    }
}
