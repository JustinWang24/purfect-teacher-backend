<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/10/19
 * Time: 7:33 AM
 */

namespace App\BusinessLogic\OA\EnquiryLogic;

use App\BusinessLogic\OA\EnquiryLogic\Impl\TimetableItemEnquiryLogic;

class Factory
{
    /**
     * @param $type
     * @return GeneralEnquiryLogic
     */
    public static function GetInstance($type){
        $instance = null;

        switch ($type){
            case GeneralEnquiryLogic::LOGIC_TIMETABLE_ENQUIRY:
                $instance = new TimetableItemEnquiryLogic();
                break;
            default:
                break;
        }

        return $instance;
    }
}