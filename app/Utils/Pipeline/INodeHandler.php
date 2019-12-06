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
     * 根据谁执行的动作, 获取下一步发送到哪些用户的方法
     * @param  User $user: 执行了操作的用户
     * @return User[]
     */
    public function getNoticeTo(User $user);
}
