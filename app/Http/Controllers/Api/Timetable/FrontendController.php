<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 15/12/19
 * Time: 10:45 PM
 */

namespace App\Http\Controllers\Api\Timetable;


use App\BusinessLogic\TimetableViewLogic\Factory;
use App\BusinessLogic\TimetableViewLogic\Impl\FromStudentPoint;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    /**
     * 为学生端 APP 或 h5 专业的接口
     * @param Request $request
     */
    public function load_by_student(Request $request){

//        dd($request->all());
        $student = $request->user();

        /**
         * @var FromStudentPoint $logic
         */
        $logic = Factory::GetInstance($request);
        return $logic->build();
    }
}