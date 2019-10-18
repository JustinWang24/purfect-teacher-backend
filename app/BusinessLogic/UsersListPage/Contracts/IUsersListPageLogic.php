<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 11:43 PM
 */

namespace App\BusinessLogic\UsersListPage\Contracts;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IUsersListPageLogic
{
    /**
     * 获取当前用户列表的上级 Model 对象
     * @return Model
     */
    public function getParentModel();

    /**
     * 获取用户列表
     * @return array|Collection
     */
    public function getUsers();

    /**
     * 获取返回按钮的 URL
     * @return string
     */
    public function getReturnPath();

    /**
     * 获取视图文件的路径
     * @return string
     */
    public function getViewPath();

    /**
     * 获取分页用的 params
     * @return array
     */
    public function getAppendedParams();
}