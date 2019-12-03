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

        if(!empty($result)){
            // 表示不是任何人都可以发起该流程
            $handlerData = array_merge($handlerData, $result);
        }
        else{
            // 任何人都可以发起流程
            $handlerData['role_slugs'] = json_encode(Arr::flatten(Role::AllTypes()));
        }
        return Handler::create($handlerData);
    }
}