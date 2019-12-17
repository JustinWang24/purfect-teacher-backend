<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecialAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('special_attendances', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id')->comment('关联的学校');
            $table->date('start_date')->comment('值周开始日期');
            $table->date('end_date')->comment('值周开始日期');
            $table->string('high_level')->comment('校级值班负责人');
            $table->string('middle_level')->comment('中级值班负责人');
            $table->string('teacher_level')->comment('教师级值班负责人');
            $table->string('grade_id')->comment('班级值班负责人');
            $table->string('related_organizations')->nullable()->comment('组织单位');
            $table->string('description')->nullable()->comment('说明文字');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('special_attendances');
    }
}
