<?php
/**
 * 表示一个用户发起的流程
 */

namespace App\Utils\Pipeline;

interface IUserFlow extends IPersistent
{
    const IN_PROGRESS   = 0; // 处理中
    const DONE          = 1; // 通过
    const TERMINATED        = 2; // 否决了
    const REVOKE = 3;//撤销

    public function getFlow(): IFlow;

    public function getUser(): IUser;

    /**
     * @return IAction[]
     */
    public function getActions();

    /**
     * 是否通过
     * @return bool
     */
    public function isDone(): bool;
    /**
     * 是否被否决
     * @return bool
     */
    public function isTerminated(): bool;
}
