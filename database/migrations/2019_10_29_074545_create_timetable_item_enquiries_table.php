<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimetableItemEnquiriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * 这是一张中间表, 链接课程表项目和请求表. 实现类似: 某节课, 在某一天有人请假等
     *
     * @return void
     */
    public function up()
    {
        Schema::create('timetable_item_enquiries', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('timetable_item_id')->comment('课程表项');
            $table->unsignedBigInteger('enquiry_id')->comment('申请');
            $table->dateTime('scheduled_at')->comment('预定的开始时间');
            $table->dateTime('end_at')->comment('预定的结束时间');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('timetable_item_enquiries');
    }
}
