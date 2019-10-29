<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 29/10/19
 * Time: 7:32 AM
 */

namespace App\BusinessLogic\OA\EnquiryLogic;

use App\Utils\ReturnData\IMessageBag;

abstract class GeneralEnquiryLogic
{
    const LOGIC_TIMETABLE_ENQUIRY = 'timetable-enquiry';

    public function __construct()
    {
    }

    /**
     * @param $data
     * @param $user
     * @return IMessageBag
     */
    public abstract function create($data, $user);
}