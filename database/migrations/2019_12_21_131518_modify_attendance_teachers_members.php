<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyAttendanceTeachersMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendance_teachers_group_members', function (Blueprint $table) {
            $table->string('mac_address')->nullable(false)->comment('手机识别码');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_teachers_group_members', function (Blueprint $table) {
            if(Schema::hasColumn('attendance_teachers_group_members','mac_address')){
                $table->dropColumn('mac_address');
            }
        });
    }
}
