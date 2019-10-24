<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Schools\SchoolDao;
use App\User;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class TimeSlotsController extends Controller
{
    /**
     * 根据指定的学校 uuid 返回作息时间表
     * @param Request $request
     * @return mixed
     */
    public function load_by_school(Request $request){
        $schoolUuid = $request->get('school');

        $schoolDao = new SchoolDao(new User());

        $school = $schoolDao->getSchoolByUuid($schoolUuid);

        if($school){
            return JsonBuilder::Success(['time_frame'=>$school->timeFrame]);
        }
        else{
            return JsonBuilder::Error();
        }
    }
}
