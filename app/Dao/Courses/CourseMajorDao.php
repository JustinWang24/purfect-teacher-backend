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
}