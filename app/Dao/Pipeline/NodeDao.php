<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 11:08 AM
 */

namespace App\Dao\Pipeline;

use App\Models\Pipeline\Flow\Flow;
use App\Models\Pipeline\Flow\Node;
use Illuminate\Support\Facades\DB;
use App\Models\Pipeline\Flow\Handler;

class NodeDao
{
    /**
     * 插入链表, 任何增加 node 的操作, 都调用此方法即可
     * @param $data
     * @param Flow $flow
     * @param null $prevNode
     * @return null|Node
     */
    public function insert($data, Flow $flow, $prevNode = null){
        $data['flow_id'] = $flow->id;

        if(!$prevNode){
            // 如果没有传入前一步, 那么表示要创建的 node 的上一步是流程的最后一步.
            // 因为流程在创建的时候, 是永远默认创建了头部 node 的, 所以 getTail 总是可以取到至少 head 的
            $prevNode = $flow->getTailNode();
        }

        if($prevNode){
            DB::beginTransaction();
            try{
                $originNext = $prevNode->next; // 原本的下一个节点

                /**
                 * 由于 nodes 是个双向链表, 所以新增node 的操作, 实际上应该理解为插入
                 * 前面的 node 的 next, 应该指向新建的 node
                 * 前面 node 的原来的 next, 应该作为新建的 node 的 next
                 */
                $data['next_node'] = $prevNode->next_node;
                $data['prev_node'] = $prevNode->id;
                $currentNode = Node::create($data);
                $prevNode->next_node = $currentNode->id;
                $prevNode->save();

                if($originNext){
                    // 如果原来的下一个节点存在, 那么由于插入的操作, 原来的next 节点与新节点形成新的链接顺序
                    $originNext->prev_node = $currentNode->id;
                    $originNext->save();
                }

                DB::commit();
                return $currentNode;
            }
            catch (\Exception $exception){
                DB::rollBack();
                dump($exception->getMessage());
                return null;
            }
        }
        else{
            // 如果还是没有取到 prev 的节点, 那么相当于是创建 head 节点
            return Node::create($data);
        }
    }

    /**
     * @param $id
     * @return Node
     */
    public function getById($id){
        return Node::find($id);
    }

    /**
     * 获取指定流程的第一步
     * @param Flow|int $flow
     * @return Node|null
     */
    public function getHeadNodeByFlow($flow){
        $flowId = $flow;
        if(is_object($flow)){
            $flowId = $flow->id;
        }
        return Node::where('flow_id',$flowId)->where('prev_node',0)->first();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function delete($id){

        $node = $this->getById($id);
        $result = false;

        if($node && $node->prev_node !== 0){
            // 前一个 node 的 next 应该被更新为 当前 node 的 next, 保证链表的链接正确; 永远不能删除流程中的第一步: 发起
            $prev = $node->prev;
            $currentNextNode = $node->next_node;
            $prev->next_node = $currentNextNode;
            DB::beginTransaction();
            try{
                $prev->save();
                Node::where('id',$id)->delete();
                DB::commit();
                $result = true;
            }
            catch (\Exception $exception){
                DB::rollBack();
                $result = $exception->getMessage();
            }
        }

        return $result;
    }
}