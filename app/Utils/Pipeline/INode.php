<?php
/**
 * 这个是简单流程中的节点
 * 流程中的审批人的抽象; 审批人可能是动态的 ( 即前一步的审批人的上级, 需要即时查询) 或者 静态的( 固定的某人 )
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:07 PM
 */

namespace App\Utils\Pipeline;

interface INode extends IPersistent
{
    const TYPE_SIMPLE  = 1; // 简单节点: 出入口的数量 <= 1
    const TYPE_COMPLEX = 2; // 复杂节点: 表示有多个出入口

    /**
     * 是否是起始点
     * @return boolean
     */
    public function isHead();

    /**
     * 是否是结束点, 最终的审批者
     * @return boolean
     */
    public function isEnd();

    /**
     * 是否为动态节点
     * @return boolean
     */
    public function isDynamic();

    /**
     * 前一个节点
     * @return INode|null
     */
    public function getPrev();

    /**
     * 后一个节点
     * @return INode|null
     */
    public function getNext();

    /**
     * 获取名字
     * @return string
     */
    public function getName(): string ;

    /**
     * 获取该步骤的最后一次执行
     * @return IAction
     */
    public function getLastAction(): IAction;

    /**
     * 获取步骤的处理器
     * @return INodeHandler
     */
    public function getHandler(): INodeHandler;
}
