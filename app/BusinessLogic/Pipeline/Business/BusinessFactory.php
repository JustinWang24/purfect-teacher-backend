<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 10:25 PM
 */

namespace App\BusinessLogic\Pipeline\Business;
use App\BusinessLogic\Pipeline\Flow\Business\Impl\MacAddressLogic;
use App\Utils\Pipeline\IFlow;

class BusinessFactory
{
    /**
     * @param $business
     * @param $user
     * @return MacAddressLogic|null
     */
    public static function GetInstance($business, $user)
    {

        $instance = null;
        switch ($business){
            case IFlow::BUSINESS_TYPE_MACADDRESS:
                $instance = new MacAddressLogic($user);
                break;
            default:
                break;
        }
        return $instance;
    }
}
