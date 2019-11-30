<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAttendanceTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_schedules', function (Blueprint $table) {
            $table->renameColumn('attendance_id', 'task_id');
        });
        Schema::table('attendance_time_slots', function (Blueprint $table) {
            $table->renameColumn('attendance_id', 'task_id');
        });
        Schema::table('attendance_schedule_persons', function (Blueprint $table) {
            $table->renameColumn('attendance_id', 'task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_schedules', function (Blueprint $table) {
            $table->renameColumn('task_id', 'attendance_id');
        });
        Schema::table('attendance_time_slots', function (Blueprint $table) {
            $table->renameColumn('task_id', 'attendance_id');
        });
        Schema::table('attendance_schedule_persons', function (Blueprint $table) {
            $table->renameColumn('task_id', 'attendance_id');
        });
    }
}
