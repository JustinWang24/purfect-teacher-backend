<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNodeOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pipeline_node_options', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('node_id')->comment('关联的流程步骤');
            $table->string('name')->comment('选填项目名称');
            $table->string('type')->comment('选填项目类型: 文字或日期');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pipeline_node_options');
    }
}
