<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressStepsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_steps_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('preset_step_id')->nullable()->comment('预置步骤ID');
            $table->unsignedBigInteger('user_id')->nullable()->comment('步骤负责人');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE progress_steps_users comment '步骤负责人表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progress_steps_users');
    }
}
