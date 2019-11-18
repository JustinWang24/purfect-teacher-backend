<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCourseElectivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_electives', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('选修课状态:0未开始, 1报名中, 2已截止');
            $table->unsignedSmallInteger('max_num')->default(1)->nullable()->comment('最大人数');
            $table->timestamp('expired_at')->nullable()->comment('录取截止时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
