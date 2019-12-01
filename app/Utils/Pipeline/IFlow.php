<?php
/**
 * 流程审批的抽象设计
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:17 PM
 */

namespace App\Utils\Pipeline;


interface IFlow
{
    /**
     * 返回当前流程中等待处理的步骤
     * @return INode
     */
    public function getCurrentPendingNode(): INode;

    /**
     * 设置传入的 node 为当前流程
     *
     * @param INode $node
     * @return boolean
     */
    public function setCurrentPendingNode(INode $node);
}