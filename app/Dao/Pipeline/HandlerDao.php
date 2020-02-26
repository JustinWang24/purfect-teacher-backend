<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 1:44 PM
 */

namespace App\Dao\Pipeline;

use App\Models\Pipeline\Flow\Handler;
use App\Models\Pipeline\Flow\Node;
use App\Utils\Pipeline\NodeHandlersDescriptor;
use Illuminate\Support\Arr;
use App\Models\Acl\Role;

class HandlerDao
{
    /**
     * @param Node $node
     * @param array $handlersDescriptor: 包含了描述哪些用户可以操作这个节点的数据
     * @return Handler
     */
    public function create(Node $node, $handlersDescriptor = []){
        $handlerData = [
            'node_id' => $node->id,
        ];
        $result = NodeHandlersDescriptor::Parse($handlersDescriptor);

        /**
         * 目标的用户, 只能从部门和用户群中取一个, organizations 优先
         */
        if(empty($result['organizations'])){
            $handlerData['organizations'] = null;
            $handlerData['titles'] = $result['titles'];
            $handlerData['role_slugs'] = $result['role_slugs'];
        }
        else{
            $handlerData['organizations'] = $result['organizations'];
            $handlerData['titles'] = $result['titles'];
            $handlerData['role_slugs'] = $result['role_slugs'];
        }
        if(!empty($result['notice_to'])){
            $handlerData['notice_to'] = $result['notice_to'];
        }
        if (!empty($result['notice_organizations'])) {
            $handlerData['notice_organizations'] = $result['notice_organizations'];
        }
        return Handler::create($handlerData);
    }

    public function update(Node $node, $handlersDescriptor) {

        $handlerData = [];
        $result = NodeHandlersDescriptor::Parse($handlersDescriptor);
        /**
         * 负责审核的角色
         */
        if(!empty($result['notice_to'])){
            $handlerData['notice_to'] = $result['notice_to'];
        }
        if (!empty($result['notice_organizations'])) {
            $handlerData['notice_organizations'] = $result['notice_organizations'];
        }
        return Handler::where('id', $node->handler->id)->update($handlerData);
    }
}
