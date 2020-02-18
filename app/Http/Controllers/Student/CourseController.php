<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace App\Http\Controllers\Student;

use App\Dao\Courses\CourseDao;
use App\Dao\Courses\Lectures\LectureDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\User;

class CourseController extends Controller
{
    /**
     * 学生加载自己的课程
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manager(MyStandardRequest $request){
        $courseId = $request->getCourseId();
        $this->dataForView['course'] = (new CourseDao())->getCourseById($courseId);
        /**
         * @var User $student
         */
        $student = $request->user();
        $this->dataForView['student'] = $student;

        $timetableItemDao = new TimetableItemDao();
        $items = $timetableItemDao->getItemsByCourseAndGrade($courseId, $student->gradeUser->grade_id);

        if($items->count() > 0){
            $teacher = $items[0]->teacher;
            $lectures = (new LectureDao())->getLecturesByCourseAndTeacher($courseId, $teacher->id);
            $this->dataForView['lectures'] = $lectures;
            return view('student.courses.manager', $this->dataForView);
        }
    }
}