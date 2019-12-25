<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExpendTeachersProfiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->string('work_start_at',40)->comment('参加工作时间')->nullable();
            $table->string('major',40)->comment('第一学历专业')->nullable();
            $table->string('final_education',40)->comment('最高学历')->nullable();
            $table->string('final_major',40)->comment('最高学历专业')->nullable();
            $table->string('title_start_at',40)->comment('职称取得时间')->nullable();

            $table->string('title1_at',50)->comment('原职称取得时间')->nullable();
            $table->string('title1_hired_at',50)->comment('原职称聘任时间')->nullable();

            $table->string('hired_at',40)->comment('聘任时间')->nullable();
            $table->boolean('hired')->comment('是否聘任')->default(true);
            $table->text('notes')->comment('备注')->nullable();
            $table->string('serial_number')->nullable()->change();
            $table->string('group_name')->nullable()->change();
            $table->string('avatar')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::table('teacher_profiles', function (Blueprint $table) {
            $table->dropColumn('work_start_at');
            $table->dropColumn('major');
            $table->dropColumn('final_education');
            $table->dropColumn('final_major');
            $table->dropColumn('title_start_at');
            $table->dropColumn('title1_at');
            $table->dropColumn('title1_hired_at');
            $table->dropColumn('hired_at');
            $table->dropColumn('hired');
            $table->dropColumn('notes');
        });
    }
}
