<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/10/19
 * Time: 1:55 AM
 */

namespace App\Dao\Courses;

use App\Models\Courses\CourseMajor;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CourseMajorDao
{
    public function __construct()
    {

    }

    /**
     * 根据指定的专业 Major id 获取课程列表
     * @param $majorId
     * @param bool $simple
     * @return Collection
     */
    public function getCoursesByMajor($majorId, $simple = true){
        if($simple){
            return CourseMajor::select(DB::raw('course_id as id, course_name as name, course_code as code'))
                ->where('major_id',$majorId)->get();
        }
        return CourseMajor::where('major_id',$majorId)->get();
    }

    /**
     * 根据指定的专业 Major id 获取课程列表
     * @param $majorId
     * @param $termIdx
     * @param bool $simple
     * @return Collection|array
     */
    public function getCoursesByMajorAndTerm($majorId, $termIdx, $simple = true){
        $cms =  DB::table('course_majors')
            ->join('courses', function ($join) use ($termIdx) {
                $join->on('courses.id', '=', 'course_majors.course_id')
                    ->where('courses.term','=',$termIdx);
            })
            ->where('major_id',$majorId)
            ->orderBy('course_majors.course_name','asc')
            ->get();

        $data = [];
        if($simple){
            foreach ($cms as $cm) {
                $data[] = [
                    'id'=>$cm->course_id,
                    'name'=>$cm->name
                ];
            }
        }

        return $simple ? $data : $cms;
    }


    /**
     * 根据指定的专业Id和年级获取课程列表
     * @param $majorId
     * @param $year
     * @param bool $simple
     * @return Collection|array
     */
    public function getCoursesByMajorAndYear($majorId, $year, $simple = true){
        $cms =  DB::table('course_majors')
            ->join('courses', function ($join) use ($year) {
                $join->on('courses.id', '=', 'course_majors.course_id')
                    ->where('courses.year','=',$year);
            })
            ->where('major_id',$majorId)
            ->orderBy('course_majors.course_name','asc')
            ->get();

        $data = [];
        if($simple){
            foreach ($cms as $cm) {
                $data[] = [
                    'id'=>$cm->course_id,
                    'name'=>$cm->name
                ];
            }
        }

        return $simple ? $data : $cms;
    }


    /**
     * 通过专业id集合获取课程和教程
     * @param $majorIdArr
     * @return mixed
     */
    public function getCourseIdByMajorIdArr($majorIdArr) {
        return CourseMajor::whereIn('major_id', $majorIdArr)->with(['course.courseTextbooks.textbook'])->get();
    }

    /**
     * 根据专业ID获取选修课
     * @param $majorId
     * @return CourseMajor
     */
    public function getElectiveCourseByMajorId($majorId)
    {
        return CourseMajor::where('major_id', $majorId)
               ->select('course_id', 'course_name')
               ->join('majors', 'majors.id', '=', 'course_majors.major_id')
               ->with([
                   'courseArrangement' => function($query) {
                        $query->select('course_id', 'week', 'day_index');
                   },
                   'courseElective' => function($query) {
                        $query->select('course_id', 'open_num');
                   },
                   'courseTeacher' => function($query) {
                        $query->select('course_id', 'teacher_name');
                   },
                   '' => function($query) {
                        $query->count();
                   }
               ])->get();
    }


    /**
     * @param $courseId
     * @param $keyword
     * @return mixed
     */
    public function getCourseMajorByCourseIdsAndCourseName($courseId, $keyword) {
        return CourseMajor::where('course_name','like',$keyword.'%')
            ->whereIn('course_id',$courseId)->get();
    }
}
