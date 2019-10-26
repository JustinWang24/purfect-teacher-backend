<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDraftInTimetableItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('timetable_items', function (Blueprint $table) {
            // 课程表需要一个 flag 来标识是否是草稿, 否则一旦添加, 就立即生效不符合流程
            // 比如更改了授课教师, 需要通知对方确认, 或者进入某个审核流程才行

            $table->boolean('published')->default(false)
                ->comment('是草稿还是正式发布, 默认为草稿');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
