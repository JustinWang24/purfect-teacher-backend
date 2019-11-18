<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplyDayIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apply_day_index', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('week_id')->nullable()->comment('apply_week主键id');
            $table->unsignedSmallInteger('day_index')->nullable()->comment('需要上课的天的序号');
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
        Schema::dropIfExists('apply_day_index');
    }
}
