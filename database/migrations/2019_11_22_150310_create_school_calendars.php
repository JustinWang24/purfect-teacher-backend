<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSchoolCalendars extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_calendars', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('school_id')->default(0)->comment('学校id');
            $table->string('tag', 100)->comment('事件标签');
            $table->string('content')->comment('事件内容');
            $table->timestamp('event_time')->comment('事件时间');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE school_calendars comment '校历表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_calendars');
    }
}
