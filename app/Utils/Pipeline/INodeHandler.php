<?php
/**
 * 与节点之间, 是一对多的关系
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:16 PM
 */

namespace App\Utils\Pipeline;

use App\User;

interface INodeHandler extends IPersistent
{
    /**
     * 以下定义用于在发送消息通知时, 定位消息的接收者
     */
    const CLASS_ADVISER = '班主任';
    const GRADE_ADVISER = '年级组长';
    const ORGANIZATION_DEPUTY = '部门副职领导'; // 用户所在科室/机构/教研组的副职领导
    const ORGANIZATION_LEADER = '部门正职领导';
    const DEPARTMENT_LEADER = '系主任';
    const SCHOOL_DEPUTY = '副校长';
    const SCHOOL_PRINCIPLE = '校长';
    const SCHOOL_COORDINATOR = '书记';

    /**
     * 流程中某一节点的具体处理逻辑, 并返回处理的结果
     * @param INode $node
     * @return IAction
     */
    public function handle(INode $node): IAction;

    /**
     * 流程中某个节点需要被驳回的处理逻辑
     * @param INode $node
     * @return IAction
     */
    public function resume(INode $node): IAction;

    /**
     * 获取消息发送到哪些用户的方法
     * @return User[]
     */
    public function getNoticeTo();
}
