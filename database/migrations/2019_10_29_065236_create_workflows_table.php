<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkflowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workflows', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('school_id');
            $table->string('name')->comment('流程名称');
            $table->text('process_descriptor')->comment('描述流程走向的结构字符串');

            $table->timestamps();
        });

        Schema::create('workflow_steps', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('workflow_id');
            $table->unsignedSmallInteger('step_index')->comment('表示是第几个步骤');
            $table->string('name')->comment('流程步骤名称');
            $table->text('step_descriptor')->comment('描述流程走向的结构字符串');

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
        Schema::dropIfExists('workflows');
        Schema::dropIfExists('workflow_steps');
    }
}
