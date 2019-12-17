<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 3:35 PM
 */

namespace App\BusinessLogic\TimetableViewLogic;


use App\BusinessLogic\TimetableViewLogic\Contracts\ITimetableBuilder;
use App\BusinessLogic\TimetableViewLogic\Impl\FromCourseView;
use App\BusinessLogic\TimetableViewLogic\Impl\FromGradePoint;
use App\BusinessLogic\TimetableViewLogic\Impl\FromRoomPoint;
use App\BusinessLogic\TimetableViewLogic\Impl\FromStudentPoint;
use App\BusinessLogic\TimetableViewLogic\Impl\FromTeacherPoint;
use Illuminate\Http\Request;

class Factory
{
    /**
     * @param Request $request
     * @return ITimetableBuilder|null
     */
    public static function GetInstance(Request $request){
        $instance = null;
        if($request->has('grade')){
            // 表示从班级的角度来加载课程表
            $instance = new FromGradePoint($request);
        }
        elseif ($request->has('course')){
            $instance = new FromCourseView($request);
        }
        elseif ($request->has('teacher')){
            $instance = new FromTeacherPoint($request);
        }
        elseif ($request->has('room')){
            $instance = new FromRoomPoint($request);
        }
        elseif ($request->has('student')){
            $instance = new FromStudentPoint($request);
        }
        return $instance;
    }


}