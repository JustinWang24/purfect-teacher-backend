<?php

use Illuminate\Database\Seeder;

class PipelineTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $flow = \App\Models\Pipeline\Flow\Flow::create([
            'school_id' => 1,
            'type' => \App\Models\Pipeline\Flow\Flow::TYPE_2_01,
            'name' => '奖学金',
            'icon' => '',
            'copy_uids' => '168008',
            'business' => ''
        ]);
        $firstNode = \App\Models\Pipeline\Flow\Node::create([
            'flow_id' => $flow->id,
            'prev_node' => 0,
            'next_node' => 0,
            'thresh_hold' => 1,
            'type' => 1,
            'dynamic' => 1,
            'name' => '发起申请流程',
            'description' => ''
        ]);
        $firstHandler = \App\Models\Pipeline\Flow\Handler::create([
            'node_id' => $firstNode->id,
            'role_slugs' => '学生;',
            'titles' => '全部成员;',
            'notice_to' => '班主任;',
        ]);
        $nextNode = \App\Models\Pipeline\Flow\Node::create([
            'flow_id' => $flow->id,
            'prev_node' => $firstNode->id,
            'next_node' => 0,
            'thresh_hold' => 1,
            'type' => 1,
            'dynamic' => 1,
            'name' => '审批流程',
            'description' => ''
        ]);
        $nextHandler = \App\Models\Pipeline\Flow\Handler::create([
            'node_id' => $nextNode->id,
            'role_slugs' => '教师;职工;学生;',
            'titles' => '班主任;',
            'notice_to' => '系主任;'
        ]);
        $firstNode->next_node = $nextNode->id;
        $firstNode->save();

        $endNode = \App\Models\Pipeline\Flow\Node::create([
            'flow_id' => $flow->id,
            'prev_node' => $nextNode->id,
            'next_node' => 0,
            'thresh_hold' => 1,
            'type' => 1,
            'dynamic' => 1,
            'name' => '审批流程',
            'description' => ''
        ]);
        $endHandler = \App\Models\Pipeline\Flow\Handler::create([
            'node_id' => $endNode->id,
            'role_slugs' => '教师;职工;学生;',
            'titles' => '系主任;',
        ]);
        $nextNode->next_node = $endNode->id;
        $nextNode->save();

        $option1 = \App\Models\Pipeline\Flow\NodeOption::create([
            'node_id' => $firstNode->id,
            'name' => '文本框',
            'type' => '文本',
            'title' => 'title',
            'tips' => '请输入文本内容',
            'required' => 1
        ]);
        $option2 = \App\Models\Pipeline\Flow\NodeOption::create([
            'node_id' => $firstNode->id,
            'name' => '单选框',
            'type' => '文本',
            'title' => 'title',
            'tips' => '请选择性别',
            'required' => 0,
            'extra' => json_encode([['id' => 1, 'name' => '男'], ['id' => 2, 'name' => '女']])
        ]);
    }
}
