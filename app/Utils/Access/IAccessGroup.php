<?php
/**
 * 权限组
 */

namespace App\Utils\Access;


interface IAccessGroup
{
    /**
     * 获取权限组包含的所有权限
     * @return IAccess[]
     */
    public function getAccesses();

    /**
     * 获取包含的所有子权限组
     * @return IAccessGroup[]
     */
    public function getGroups();

    /**
     * 将权限推入权限组
     * @param IAccess $access
     * @return IAccessGroup
     */
    public function push(IAccess $access);

    /**
     * 将某个权限从权限组中移除
     * @param IAccess $access
     * @return IAccessGroup
     */
    public function remove(IAccess $access);
}