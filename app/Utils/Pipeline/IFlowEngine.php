<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 5/12/19
 * Time: 9:30 AM
 */

namespace App\Utils\Pipeline;


interface IFlowEngine
{
    public function start(INode $headNode, IAction $startAction);   // 开始流程
    public function process(); // 处理流程, 向前一步
    public function resume();  // 处理流程, 向后一步

    /**
     * 获取流程
     * @return IFlow
     */
    public function getFlow(): IFlow;
}