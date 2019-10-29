<?php

namespace App\Http\Controllers\Api\Enquiry;

use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\BusinessLogic\OA\EnquiryLogic\Factory;

class EnquiriesController extends Controller
{
    /**
     * @param Request $request
     * @return string
     */
    public function save(Request $request){
        $enquiryData = $request->get('enquiry');
        $logic = Factory::GetInstance($request->get('logic'));
        $msg = $logic->create($enquiryData, $request->get('userUuid'));

        if($msg->isSuccess()){
            return JsonBuilder::Success(['enquiry'=>$msg->getData()]);
        }else{
            return JsonBuilder::Error($msg->getMessage(), $msg->getCode());
        }
    }
}
