<?php
/**
 * 与节点之间, 是一对多的关系
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:16 PM
 */

namespace App\Utils\Pipeline;

interface INodeHandler extends IPersistent
{
    /**
     * 流程中某一节点的具体处理逻辑, 并返回处理的结果
     * @param IUser $prevActionUser
     * @param IAction $prevAction
     * @param INode $nextNode
     * @return boolean
     */
    public function handle(IUser $prevActionUser, IAction $prevAction, $nextNode);

    /**
     * 流程中某个节点需要被驳回的处理逻辑
     * @param IUser $user
     * @param IFlow $flow
     * @return boolean
     */
    public function reject(IUser $user, IFlow $flow);

    /**
     * 根据谁执行的动作, 获取下一步发送到哪些用户的方法
     * @param  IUser $user: 执行了操作的用户
     * @return IUser[]
     */
    public function getNoticeTo(IUser $user);
}
