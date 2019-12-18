<?php

namespace App\Http\Controllers\Api\Evaluate;

use App\Dao\Evaluation\RateTeacherDao;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Utils\JsonBuilder;

class RatingController extends Controller
{
    /**
     * 学生评价教师的接口
     * @param Request $request
     * @return string
     */
    public function rate_lesson(Request $request){
        /**
         * @var User $student
         */
        $student = $request->user('api');
        $dao = new RateTeacherDao();

        $detail = $dao->rateTeacher(
            $student,
            $request->get('my_rate'),
            $request->get('timetable_item'),
            $request->get('teacher'),
            $request->get('course')
        );

        return $detail->isSuccess() ?
            JsonBuilder::Success(['id'=>$detail->getData()->id]) :
            JsonBuilder::Error($detail->getMessage());
    }
}
