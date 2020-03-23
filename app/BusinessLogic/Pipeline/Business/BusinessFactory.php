<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 8/12/19
 * Time: 10:25 PM
 */

namespace App\BusinessLogic\Pipeline\Business;
use App\BusinessLogic\Pipeline\Business\Impl\ClockinLogic;
use App\BusinessLogic\Pipeline\Business\Impl\LeaveLogic;
use App\BusinessLogic\Pipeline\Business\Impl\MacAddressLogic;
use App\Models\TeacherAttendance\Leave;
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
            case IFlow::BUSINESS_TYPE_CLOCKIN:
                $instance = new ClockinLogic($user);
            case IFlow::BUSINESS_TYPE_LEAVE:
                $instance = new LeaveLogic($user, Leave::SOURCE_LEAVE);
            case IFlow::BUSINESS_TYPE_AWAY:
                $instance = new LeaveLogic($user, Leave::SOURCE_AWAY);
            case IFlow::BUSINESS_TYPE_TRAVEL:
                $instance = new LeaveLogic($user, Leave::SOURCE_TRAVEL);
            default:
                break;
        }
        return $instance;
    }
}
