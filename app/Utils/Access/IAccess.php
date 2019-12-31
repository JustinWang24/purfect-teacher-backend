<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 28/12/19
 * Time: 7:43 PM
 */

namespace App\Utils\Access;

use App\Utils\Pipeline\IPersistent;
use App\Utils\Pipeline\IUser;

interface IAccess extends IPersistent
{

    /**
     * 是否给定用户可以看到
     * @param IUser $user
     * @param IOperation $operation
     * @return boolean
     */
    public function canDo(IUser $user, IOperation $operation);

    /**
     * 获取权限包含的所有可做操作
     * @return IOperation[]
     */
    public function getOperations();

    /**
     * 获取此权限的 key 值, 就是其唯一标识
     * @return string
     */
    public function getKey();

    /**
     * 根据给定的 key 获取权限
     * @param $key
     * @return IAccess|null
     */
    public function getByKey($key);
}