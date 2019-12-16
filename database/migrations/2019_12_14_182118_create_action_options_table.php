<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActionOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipeline_action_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('action_id')->comment('关联的流程动作');
            $table->unsignedBigInteger('option_id')->comment('关联的步骤的必填项 ID');
            $table->text('value')->comment('关联的步骤的必填项的结果值');
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
        Schema::dropIfExists('pipeline_action_options');
    }
}
