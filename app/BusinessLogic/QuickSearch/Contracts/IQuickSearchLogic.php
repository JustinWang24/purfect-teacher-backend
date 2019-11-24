<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 11:04 PM
 */

namespace App\BusinessLogic\QuickSearch\Contracts;

use Illuminate\Support\Collection;

interface IQuickSearchLogic
{
    /**
     * 获取搜索到的用户数据结果集
     * @return Collection
     */
    public function getUsers();

    /**
     * 获取搜索到的其他院系等数据结果集
     * @return Collection
     */
    public function getFacilities();

    /**
     * 下一步操作的路由
     *
     * @param $facility
     * @return string
     */
    public function getNextAction($facility);
}