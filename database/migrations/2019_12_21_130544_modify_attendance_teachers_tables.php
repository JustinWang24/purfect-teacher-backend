<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAttendanceTeachersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_teacher_groups', function (Blueprint $table) {
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
        });
        Schema::table('attendance_teachers', function (Blueprint $table) {
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
            $table->unsignedInteger('user_id')->nullable(false)->comment('用户id');
        });
        Schema::table('attendance_teachers_group_members', function (Blueprint $table) {
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
        });
        Schema::table('attendance_teachers_messages', function (Blueprint $table) {
            $table->unsignedInteger('school_id')->nullable(false)->comment('学校id');
            $table->renameColumn('attendance_data','attendance_date');
            $table->unsignedTinyInteger('type')->nullable(false)->comment('补卡类型1上午，2下午,3全天');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_teacher_groups', function (Blueprint $table) {
            if(Schema::hasColumn('attendance_teacher_groups','school_id')){
                $table->dropColumn('school_id');
            }
        });
        Schema::table('attendance_teachers', function (Blueprint $table) {
            if(Schema::hasColumn('attendance_teachers','school_id')){
                $table->dropColumn('school_id');
            }
            if(Schema::hasColumn('attendance_teachers','user_id')){
                $table->dropColumn('user_id');
            }
        });
        Schema::table('attendance_teachers_group_members', function (Blueprint $table) {
            if(Schema::hasColumn('attendance_teachers_group_members','school_id')){
                $table->dropColumn('school_id');
            }
        });
        Schema::table('attendance_teachers_messages', function (Blueprint $table) {
            if(Schema::hasColumn('attendance_teachers_messages','school_id')){
                $table->dropColumn('school_id');
            }
            if(Schema::hasColumn('attendance_teachers_messages','type')){
                $table->dropColumn('type');
            }
            $table->renameColumn('attendance_date','attendance_data');
        });
    }
}
