<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlowsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 流程表
        Schema::create('pipeline_flows', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('school_id')->comment('关联的学校');
            $table->unsignedSmallInteger('type')
                ->default(0)->comment('流程的分类: 比如财务, 行政');
            $table->string('name',100)->comment('流程的名称');
            $table->string('icon')->nullable()->comment('流程的图标');
            $table->softDeletes();
        });

        // 流程中的节点
        Schema::create('pipeline_nodes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('flow_id')->comment('归属的流程');
            $table->unsignedBigInteger('prev_node')
                ->default(0)
                ->comment('前一个节点,为 0 则说明它是流程中的第一步');

            $table->unsignedBigInteger('next_node')
                ->default(0)
                ->comment('下一个节点,为 0 则说明它是流程中最后一步');

            $table->unsignedSmallInteger('thresh_hold')
                ->default(1)
                ->comment('该节点被判定为通过的时候, 需要几个人同意后者完成. 默认为 1 个人');

            $table->unsignedSmallInteger('type')
                ->default(\App\Utils\Pipeline\INode::TYPE_SIMPLE)
                ->comment('节点的类型');

            $table->boolean('dynamic')
                ->default(true)
                ->comment('是否为动态节点 (动态节点: 表示管理该节点的, 不是一个特定的用户, 而是一个职务, 或者多个职务)');

            $table->string('name',100)->comment('节点的名称');
            $table->text('description')->nullable()->comment('该节点(也可以称之为步骤)的操作说明');
            $table->softDeletes();
        });

        // 流程中的处理器
        Schema::create('pipeline_handlers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('node_id')
                ->default(0)
                ->comment('归属的节点');

            // 对于是否可以使用该流程, 特别是第一步的发起流程, 要通过这个字段来判定, 基本上其实只有几种组合, 老师, 学生, 教职工
            $table->text('role_slugs')
                ->nullable()->comment('允许发起流程的角色Slug');

            // 以下三个是给审核专用的
            $table->text('user_ids')
                ->nullable()
                ->comment('所有可以审核该步骤的用户的 id 组合');

            $table->text('organizations')
                ->nullable()
                ->comment('所有可以审核该步骤的 组织的 id 组合');

            $table->text('titles')
                ->nullable()
                ->comment('所有可以审核该步骤的 组织内的职务的id 的 组合');

//            $table->unsignedBigInteger('user_id')
//                ->default(0)
//                ->comment('关联的用户的 id (可以为空) 这个为静态节点');
//
//            $table->unsignedBigInteger('organization_id')
//                ->default(0)
//                ->comment('关联的组织 (可以为空) 这个为动态节点');
//
//            $table->unsignedBigInteger('title_id')
//                ->default(0)
//                ->comment('组织内的职务 (可以为空) 这个为动态节点');
        });

        // 节点的处理以及结果
        Schema::create('pipeline_actions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('flow_id')->comment('关联的流程');
            $table->unsignedBigInteger('node_id')->comment('关联的节点');
            $table->unsignedBigInteger('user_id')->comment('执行这个操作的具体的人');

            $table->unsignedSmallInteger('result')
                ->default(\App\Utils\Pipeline\IAction::RESULT_PENDING)
                ->comment('处理的结果 (通过, 驳回, 等待)');

            $table->text('content')
                ->nullable()
                ->comment('处理结果的描述文字');

            $table->timestamps();
        });

        // 节点的处理所关联的附件
        Schema::create('pipeline_action_attachments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('action_id')->comment('关联的处理结果/动作');
            $table->unsignedBigInteger('media_id')->comment('关联的文件');
            $table->text('url')->comment('关联的文件的链接地址');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pipeline_flows');
        Schema::dropIfExists('pipeline_nodes');
        Schema::dropIfExists('pipeline_handlers');
        Schema::dropIfExists('pipeline_actions');
        Schema::dropIfExists('pipeline_action_attachments');
    }
}
