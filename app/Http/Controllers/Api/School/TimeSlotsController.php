<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Schools\SchoolDao;
use App\Dao\Timetable\TimeSlotDao;
use App\Http\Requests\MyStandardRequest;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\School;

class TimeSlotsController extends Controller
{
    public function save_time_slot(Request $request){
        $dao = new SchoolDao();
        $school = $dao->getSchoolByUuid($request->get('school'));
        if($school){
            $tsDao = new TimeSlotDao();
            $timeSlot = $request->get('timeSlot');
            $id = $timeSlot['id'];
            $ts = $tsDao->getById($id);
            if($ts && $ts->school_id === $school->id){
                unset($timeSlot['id']);
                $tsDao->update($id, $timeSlot);
                return JsonBuilder::Success();
            }
        }
        return JsonBuilder::Error('系统繁忙, 请稍候再试!');
    }

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
     * 加载所有的学习时间段, 以及学期中用来学习的总周数
     * @param Request $request
     * @return string
     */
    public function load_study_time_slots(Request $request){
        $schoolIdOrUuid = $request->get('school');
        $school = null;
        $schoolDao = new SchoolDao(new User());

        if(strlen($schoolIdOrUuid) > 10){
            $school = $schoolDao->getSchoolByIdOrUuid($schoolIdOrUuid);
            if($school){
                $schoolIdOrUuid = $school->id;
            }else{
                $schoolIdOrUuid = null;
            }
        }else{
            $school = $schoolDao->getSchoolById($schoolIdOrUuid);
        }
        if($schoolIdOrUuid && $school){
            $timeSlotDao = new TimeSlotDao();
            $field = ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM;
            return JsonBuilder::Success(
                [
                    'time_frame'=>$timeSlotDao->getAllStudyTimeSlots($schoolIdOrUuid, true),
                    'total_weeks'=>$school->configuration->$field,
                ]
            );
        }
        return JsonBuilder::Error();
    }

    /**
     * @param School $school
     * @param SchoolDao $dao
     */
    private function _getStudyWeeksCount($school, $dao){
        $dao->getSchoolConfig($school, ConfigurationTool::KEY_STUDY_WEEKS_PER_TERM);
    }
}
