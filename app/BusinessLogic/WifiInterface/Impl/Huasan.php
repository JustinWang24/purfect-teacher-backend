<?php
/**
 * Created by PhpStorm.
 * User: kui.zhang
 * Date: 19/10/19
 * Time: 12:22 AM
 */
namespace App\BusinessLogic\WifiInterface\Impl;

use App\BusinessLogic\WifiInterface\Contracts\WifiInterface;

class Huasan implements WifiInterface
{
   public function data()
   {
      echo 'Huasan';
   }
}