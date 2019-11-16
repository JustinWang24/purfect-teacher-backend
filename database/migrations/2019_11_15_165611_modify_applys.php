<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyApplys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('apply_group');
        Schema::dropIfExists('apply_week');
        Schema::dropIfExists('apply_day_index');
        Schema::dropIfExists('apply_time_slot');

        Schema::create('apply_groups', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('apply_id')->nullable()->comment('TeacherApplyElectiveCourse主键id');
            $table->timestamps();
        });
        Schema::create('apply_weeks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('group_id')->nullable()->comment('apply_group主键id');
            $table->unsignedSmallInteger('week')->nullable()->comment('需要上课的周的序号');
            $table->timestamps();
        });
        Schema::create('apply_days', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('week_id')->nullable()->comment('apply_week主键id');
            $table->unsignedSmallInteger('day')->nullable()->comment('需要上课的天的序号');
            $table->timestamps();
        });
        Schema::create('apply_time_slots', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('day_id')->nullable()->comment('apply_day主键id');
            $table->unsignedSmallInteger('time_slot_id')->nullable()->comment('需要上课的时间槽的序号');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apply_groups');
        Schema::dropIfExists('apply_weeks');
        Schema::dropIfExists('apply_day_indexs');
        Schema::dropIfExists('apply_time_slots');
    }
}
