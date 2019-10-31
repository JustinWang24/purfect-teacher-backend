<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProgressLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('progress_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('progress_step_id')->nullable()->comment('学校的流程步骤ID');
            $table->string('action','50')->nullable()->comment('动作 通过,驳回');
            $table->string('by_user')->nullable()->comment('操作人');
            $table->string('reason')->comment('操作原因');
            $table->timestamps();
        });

        DB::statement(" ALTER TABLE progress_logs comment '公文操作流程记录表' ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('progress_logs');
    }
}
