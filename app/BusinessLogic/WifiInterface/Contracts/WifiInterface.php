<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 05/11/19
 * Time: 11:11 PM
 */

namespace App\BusinessLogic\WifiInterface\Contracts;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface WifiInterface
{
   /**
    * Func 添加账户
    * @return false|array
    */
   public function addAccount();

   /**
    * Func 修改用户信息(这里用到只有修改wifi时长)
    * @return true|false
    */
   public function editAccount();

   /**
    * Func 账号密码修改
    * @return true|false
    */
   public function editAccountPassword();

   /**
    * Func 注销账户
    * return true|false
    */
   public function closeAccount();

   /**
    * Func 修改账号服务组
    * @return true|false
    */
   public function editAccountServiceGroup();

   /**
    * Func 账号上线操作
    * @return true|false
    */
   public function AccountOnline();

   /**
    * Func 账号下线操作
    * @return true|false
    */
   public function AccountOffline();

   /**
    * Func 查询账号是否在线
    * @return true|false
    */
   public function checkAccountOnline();
}