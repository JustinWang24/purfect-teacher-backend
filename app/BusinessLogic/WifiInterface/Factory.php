<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 18/10/19
 * Time: 11:42 PM
 */

namespace App\BusinessLogic\WifiInterface;

use App\BusinessLogic\WifiInterface\Contracts\WifiInterface;
use App\BusinessLogic\WifiInterface\Impl\Huawei;
use App\BusinessLogic\WifiInterface\Impl\Huasan;
use App\BusinessLogic\WifiInterface\Impl\CityHot;

class Factory
{
   const SCHOOL_HUAWEI   = 1; // 华为
   const SCHOOL_HUASAN   = 2; // 华三
   const SCHOOL_CITY_HOT = 3; // 城市热点

   /**
     * @param Request $request
     * @return IUsersListPageLogic|null
     */
    public static function produce($type)
    {
        $instance = null;
        switch ($type)
        {
           case self::SCHOOL_HUAWEI:
                $instance = new Huawei();
                break;
           case self::SCHOOL_HUASAN:
              $instance = new Huasan();
              break;
           case self::SCHOOL_CITY_HOT:
              $instance = new CityHot();
              break;
            default:
                break;
        }
        return $instance;
    }
}