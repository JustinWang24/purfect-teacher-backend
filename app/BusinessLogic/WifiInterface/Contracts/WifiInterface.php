<?php
/**
 * Created by PhpStorm.
 * User: kui.zhang
 * Date: 05/11/19
 * Time: 11:11 PM
 */

namespace App\BusinessLogic\WifiInterface\Contracts;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface WifiInterface
{
   /**
    * Func 注销账户
    * return true|false
    */
   public function closeAccount();

}