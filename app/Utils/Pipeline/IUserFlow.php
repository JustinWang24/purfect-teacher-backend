<?php
/**
 * 表示一个用户发起的流程
 */

namespace App\Utils\Pipeline;

interface IUserFlow extends IPersistent
{
    public function getFlow(): IFlow;

    public function getUser(): IUser;

    /**
     * @return IAction[]
     */
    public function getActions();

    /**
     * 是否结束
     * @return bool
     */
    public function isDone(): bool;
}