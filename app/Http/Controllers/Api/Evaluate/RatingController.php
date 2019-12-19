<?php

namespace App\Http\Controllers\Api\Evaluate;

use App\Dao\Evaluation\RateTeacherDao;
use App\Dao\Students\LearningNoteDao;
use App\Dao\Timetable\TimetableItemDao;
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

    /**
     * @param Request $request
     * @return string
     */
    public function save_note(Request $request){
        $user = $request->user('api');
        $itemId = $request->get('timetable_item');
        $dao = new TimetableItemDao();
        $item = $dao->getItemById($itemId);
        $noteDao = new LearningNoteDao();
        $note = $noteDao->create([
            'student_id'=>$user->id,
            'year'=>$item->year,
            'term'=>$item->term,
            'timetable_item_id'=>$itemId,
            'note'=>$request->get('my_note'),
        ]);
        return $note ? JsonBuilder::Success(['note'=>$note]) : JsonBuilder::Error();
    }
}
