<?php
/**
 * Created by PhpStorm.
 * User: kui.zhang
 * Date: 05/11/19
 * Time: 12:11 AM
 */
namespace App\BusinessLogic\WifiInterface\Impl;

use Illuminate\Http\Request;
use App\BusinessLogic\WifiInterface\Contracts\WifiInterface;

class CityHot implements WifiInterface
{
   public function data()
   {
      echo 'CityHot';
   }

}