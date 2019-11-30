<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyTimeSlot extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apply_time_slot', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('day_index_id')->nullable()->comment('apply_day_index主键id');
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
        Schema::dropIfExists('apply_time_slot');
    }
}
