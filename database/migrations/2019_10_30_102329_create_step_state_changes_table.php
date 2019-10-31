<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStepStateChangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('step_state_changes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('official_document_id')->nullable()->comment('公文ID');
            $table->unsignedBigInteger('progress_step_id')->nullable()->comment('学校的流程步骤ID');
            $table->unsignedSmallInteger('status')->comment('公文状态');
            $table->string('note')->comment('备注');
            $table->timestamps();
        });
        DB::statement(" ALTER TABLE step_state_changes comment '步骤状态变更表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('step_state_changes');
    }
}
