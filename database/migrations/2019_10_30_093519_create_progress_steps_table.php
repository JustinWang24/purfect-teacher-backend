<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_steps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('progress_id')->nullable()->comment('流程ID');
            $table->unsignedBigInteger('preset_step_id')->nullable()->comment('预置步骤ID');
            $table->smallInteger('index')->nullable()->comment('第几步骤');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE progress_steps comment '流程步骤表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progress_steps');
    }
}
