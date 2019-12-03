<?php

namespace Tests\Unit\Pipeline;

use App\Dao\Pipeline\FlowDao;
use App\Dao\Pipeline\NodeDao;
use App\Models\Pipeline\Flow\Flow;
use App\Models\Pipeline\Flow\Node;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class FlowDaoTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testItCanCreateAFlow()
    {
        $dao = new FlowDao();

        $result = $dao->create([
            'name'=>'请病假',
            'school_id'=>1,
            'type'=>IFlow::TYPE_1,
        ],'请病假必须提交病假条');

        $this->assertTrue($result->isSuccess());

        /**
         * @var Flow $flow
         */
        $flow = $result->getData();
        /**
         * @var Node $head
         */
        $head = $flow->getHeadNode();
        $this->assertNotNull($head);
        $this->assertTrue($head->isHead());
        $this->assertTrue($head->isEnd());
        $this->assertNotNull($head->handler);

        $nodeDao = new NodeDao();

        $secondNode = $nodeDao->insert([
            'name'=>'班主任审核',
            'description'=>'班主任需要检查学生的病假条, 然后回复批准或者不批准'
        ], $flow);
        $this->assertNotNull($secondNode);
        $this->assertFalse($secondNode->prev->isEnd());
        $this->assertTrue($secondNode->isEnd());
        $this->assertEquals($head->id, $secondNode->prev->id);

        // 插入操作
        $head = $flow->getHeadNode();
        $tail = $flow->getTailNode();
        $thirdNode = $nodeDao->insert([
            'name'=>'班长审核',
            'description'=>'先于班主任审核一下'
        ],$flow, $head);

        $this->assertNotNull($thirdNode);
        $this->assertEquals($head->id, $thirdNode->prev->id);
        $this->assertEquals($tail->id, $thirdNode->next->id);

        // 最终确认
        $first = $flow->getHeadNode();
        $second = $first->next;
        $this->assertEquals('班长审核',$second->name);
        $third = $second->next;
        $this->assertEquals('班主任审核',$third->name);
    }

    public function testFlowCanGetSimpleNodesLink(){
        $dao = new FlowDao();
        $flow = $dao->getById(1);

        $nodes = $flow->getSimpleLinkedNodes();

        $ut = $this;
        $nodes->each(function ($node, $key) use ($ut, $nodes){
            /**
             * @var Node $node
             */
            if($key === 0){
                $ut->assertTrue($node->isHead());
            }
            elseif ($key > 0 && $key < count($nodes)){
                $ut->assertEquals($node->prev_node, $nodes[$key-1]->id);
            }
            else{
                $ut->assertTrue($node->isEnd());
            }
        });
    }
}
