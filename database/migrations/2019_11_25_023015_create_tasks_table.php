<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oa_project_tasks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedTinyInteger('status')->default(1)->comment('关联的项目');
            $table->unsignedBigInteger('project_id')->comment('关联的项目');
            $table->unsignedBigInteger('user_id')->comment('发起人');
            $table->string('title',200)->comment('任务标题');
            $table->text('content')->nullable()->comment('任务内容');
            $table->timestamps();
        });

        Schema::create('oa_project_task_discussions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('project_task_id')->comment('关联的任务');
            $table->unsignedBigInteger('user_id')->comment('发起人');
            $table->text('content')->nullable()->comment('讨论的内容');
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
        Schema::dropIfExists('oa_project_tasks');
        Schema::dropIfExists('oa_project_task_discussions');
    }
}
