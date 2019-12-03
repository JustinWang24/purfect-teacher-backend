<?php
/**
 * 流程审批的抽象设计
 * User: justinwang
 * Date: 1/12/19
 * Time: 11:17 PM
 */

namespace App\Utils\Pipeline;

use App\User;

interface IFlow extends IPersistent
{
    // 以下随便定义了几个流程的分类
    const TYPE_1 = 1;
    const TYPE_2 = 2;
    const TYPE_3 = 3;
    const TYPE_4 = 4;

    const TYPE_1_TXT = '内外勤管理';
    const TYPE_2_TXT = '流程应用';
    const TYPE_3_TXT = '行政管理';
    const TYPE_4_TXT = '公文流转';

    /**
     * 获取流程的第一步
     * @return INode|null
     */
    public function getHeadNode();

    /**
     * 获取流程的最后一步
     * @return INode|null
     */
    public function getTailNode();

    /**
     * 返回当前流程中等待处理的步骤
     * @param User $user
     * @return IAction
     */
    public function getCurrentPendingAction(User $user): IAction;

    /**
     * 设置传入的 node 为当前流程
     *
     * @param INode $node
     * @param User $user
     * @return boolean
     */
    public function setCurrentPendingNode(INode $node, User $user);
}