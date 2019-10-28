<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Schools\SchoolDao;
use App\Dao\Timetable\TimeSlotDao;
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

        $school = $schoolDao->getSchoolByIdOrUuid($schoolUuid);

        if($school){
            return JsonBuilder::Success(['time_frame'=>$school->timeFrame]);
        }
        else{
            return JsonBuilder::Error();
        }
    }

    /**
     * 加载所有的学习时间段
     * @param Request $request
     * @return string
     */
    public function load_study_time_slots(Request $request){
        $schoolIdOrUuid = $request->get('school');
        if(strlen($schoolIdOrUuid) > 10){
            $schoolDao = new SchoolDao(new User());
            $school = $schoolDao->getSchoolByIdOrUuid($schoolIdOrUuid);
            if($school){
                $schoolIdOrUuid = $school->id;
            }else{
                $schoolIdOrUuid = null;
            }
        }
        if($schoolIdOrUuid){
            $timeSlotDao = new TimeSlotDao();
            return JsonBuilder::Success(['time_frame'=>$timeSlotDao->getAllStudyTimeSlots($schoolIdOrUuid, true)]);
        }
        return JsonBuilder::Error();
    }
}
