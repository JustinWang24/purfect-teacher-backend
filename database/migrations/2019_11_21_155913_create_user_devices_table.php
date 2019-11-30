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
            $table->enum('platform', ['ios', 'Android'])->nullable()->comment('手机平台');
            $table->string('model',50)->nullable()->comment('手机型号');
            $table->enum('type', ['iPad', 'Mobile'])->nullable()->comment('设备类型');
            $table->string('device_number', 100)->nullable()->comment('设备号');
            $table->string('push_id',100)->default(0)->comment('极光推送ID');
            $table->string('version_number')->nullable()->comment('软件版本');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE user_devices comment '用户设备表' ");

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
