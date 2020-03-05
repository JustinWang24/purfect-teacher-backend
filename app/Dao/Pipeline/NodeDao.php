<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 11:08 AM
 */

namespace App\Dao\Pipeline;

use App\Models\Pipeline\Flow\Flow;
use App\Models\Pipeline\Flow\Handler;
use App\Models\Pipeline\Flow\Node;
use App\Models\Pipeline\Flow\NodeAttachment;
use App\Models\Pipeline\Flow\NodeOption;
use App\Utils\Pipeline\NodeHandlersDescriptor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NodeDao
{
    /**
     * 插入链表, 任何增加 node 的操作, 都调用此方法即可
     * @param $data
     * @param Flow $flow
     * @param null $prevNode
     * @return Node|string
     */
    public function insert($data, Flow $flow, $prevNode = null){
        $data['flow_id'] = $flow->id;

        if(!$prevNode){
            // 如果没有传入前一步, 那么表示要创建的 node 的上一步是流程的最后一步.
            // 因为流程在创建的时候, 是永远默认创建了头部 node 的, 所以 getTail 总是可以取到至少 head 的
            $prevNode = $flow->getTailNode();
        }
        DB::beginTransaction();

        if($prevNode){
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

                // 步骤所关联的附件
                if (!empty($data['attachments'])) {
                    foreach ($data['attachments'] as $attachment){
                        if(empty($attachment['id'])){
                            $attachment['node_id'] = $currentNode->id;
                            NodeAttachment::create($attachment);
                        }
                    }
                }
                DB::commit();
                return $currentNode;
            }
            catch (\Exception $exception){
                DB::rollBack();
                return $exception->getMessage();
            }
        }
        else{
            try{
                // 如果还是没有取到 prev 的节点, 那么相当于是创建 head 节点
                $node = Node::create($data);
                // 步骤所关联的附件
                if (!empty($data['attachments'])) {
                    foreach ($data['attachments'] as $attachment){
                        if(empty($attachment['id'])){
                            $attachment['node_id'] = $node->id;
                            NodeAttachment::create($attachment);
                        }
                    }
                }
                DB::commit();
                return $node;
            }
            catch (\Exception $exception){
                DB::rollBack();
                return $exception->getMessage();
            }
        }
    }

    /**
     * 更新流程的步骤节点
     * @param $data
     * @param Node $prevNode
     * @param $flow
     * @return mixed
     */
    public function update($data, $prevNode, $flow, $organizationsLeftOver){
        DB::beginTransaction();
        try{
            /**
             * 由于 nodes 是个双向链表, 修改node 的操作, 实际上也要考虑链表元素间的关联关系
             * 前面的 node 的 next, 应该指向 当前 的 node
             * 前面 node 的原来的 next, 应该作为当前的 node 的 next
             *
             * @var Node $currentNode
             */

            $currentNode = Node::find($data['id']);

            if($prevNode && intval($currentNode->prev_node) !== $prevNode->id){
                // 表示链表发生了变化
                $prevNodeNext = $prevNode->next;

                $currentPrev = $currentNode->prev;
                $currentNext = $currentNode->next;

                $currentNode->prev_node = $prevNode->id;
                $prevNode->next_node = $currentNode->id;
                $currentNode->next_node = $prevNodeNext->id ?? 0;

                if($prevNodeNext){
                    $prevNodeNext->prev_node = $currentNode->id;
                }

                $currentPrev->next_node = isset($currentNext->id) ? $currentNext->id : 0;

                if($currentNext){
                    $currentNext->prev_node = $currentPrev->id;
                }

                $prevNode->save();
                if($prevNodeNext){
                    $prevNodeNext->save();
                }

                $currentPrev->save();
                if($currentNext){
                    $currentNext->save();
                }
            }

            $currentNode->name = $data['name'];
            $currentNode->description = $data['description'];
            $currentNode->save();

            // 关联的文档
            foreach ($data['attachments'] as $attachment){
                if(empty($attachment['id'])){
                    $attachment['node_id'] = $currentNode->id;
                    NodeAttachment::create($attachment);
                }
            }

            $handler = $currentNode->handler;

            $parsed = NodeHandlersDescriptor::Parse($data);

            $leftOverString = '';
            foreach ($organizationsLeftOver as $item) {
                $leftOverString .= $item.';';
            }

            if(isset($parsed['organizations'])){
                $parsed['organizations'] .= $leftOverString;
            }
            else{
                $parsed['organizations'] = $leftOverString;
            }

            /**
             * 目标的用户, 只能从部门和用户群中取一个, organizations 优先
             */
            if(empty($parsed['organizations'])){
                $handler->organizations = null;
                $handler->titles = null;
                $handler->role_slugs = $parsed['role_slugs'];
            }
            else{
                $handler->organizations = $parsed['organizations'];
                $handler->titles = $parsed['titles'];
                $handler->role_slugs = null;
            }

            /**
             * 审核的角色
             */
            $handler->notice_to = $parsed['notice_to']??null;

            $handler->save();
            DB::commit();
            return true;
        }
        catch (\Exception $exception){
            DB::rollBack();
            return $exception->getMessage().', 行数: '.$exception->getLine();
        }
    }

    /**
     * @param $id
     * @return Node
     */
    public function getById($id){
        return Node::where('id',$id)->with('handler')->with('options')->first();
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
        return Node::where('flow_id',$flowId)
            ->where('prev_node',0)
            ->with('handler')
            ->with('options')
            ->first();
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
            $next = $node->next;
            $currentNextNode = $node->next_node;
            $prev->next_node = $currentNextNode;
            $handler = $node->handler;
            $prevHandler = $prev->handler;
            $prevHandler->notice_to = $handler->notice_to;
            $prevHandler->notice_organizations = $handler->notice_organizations;
            DB::beginTransaction();
            try{
                if ($next) {
                    $next->prev_node = $node->prev_node;
                    $next->save();
                }
                $prevHandler->save();
                $prev->save();
                Node::where('id',$id)->delete();
                Handler::where('node_id', $id)->delete();
                NodeOption::where('node_id',$id)->delete(); // 删除选项
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

    /**
     * @param $flowId
     * @return Collection
     */
    public function getNodesByFlowId($flowId){
        return Node::where('flow_id',$flowId)
            ->with('handler')
            ->with('options')
            ->get();
    }

    /**
     * 删除步骤关联的必填项数据
     * @param $id
     * @return mixed
     */
    public function deleteOption($id){
        return NodeOption::where('id',$id)->delete();
    }

    public function deleteOptionByNode($nodeId){
        return NodeOption::where('node_id', $nodeId)->delete();
    }

    /**
     * 保存步骤关联的必填项数据
     * @param $nodeOptionData
     * @return NodeOption|null
     */
    public function saveNodeOption($nodeOptionData){
        $nodeOptionData['extra'] = is_array($nodeOptionData['extra']) ? json_encode($nodeOptionData['extra']) : $nodeOptionData['extra'];
        return NodeOption::create($nodeOptionData);
    }
}
