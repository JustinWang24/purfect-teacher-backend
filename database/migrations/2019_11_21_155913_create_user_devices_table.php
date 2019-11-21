<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_devices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->default(0)->comment('用户id');
            $table->string('device_number')->nullable()->comment('设备号');
            $table->string('push_id')->default(0)->comment('极光推送ID');
            $table->string('sn')->nullable()->comment('设备具体型号');
            $table->string('version_number')->nullable()->comment('软件版本');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE s/2019_11_19_175118_modify_teacher_apply_elective_courses.php comment '用户设备表' ");

        Schema::table('student_profiles', function (Blueprint $table) {
            $table->dropColumn('device');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_devices');
    }
}
