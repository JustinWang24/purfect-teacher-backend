<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:16 PM
 */

namespace App\Utils\Pipeline;


use App\Utils\ReturnData\IMessageBag;

interface INodeHandler
{
    /**
     * 流程中某一节点的具体处理逻辑
     * @param INode $node
     * @return IMessageBag
     */
    public function handle(INode $node): IMessageBag;

    /**
     * 流程中某个节点需要被驳回的处理逻辑
     * @param INode $node
     * @return IMessageBag
     */
    public function resume(INode $node): IMessageBag;
}