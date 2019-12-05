<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/10/19
 * Time: 2:15 AM
 */

namespace App\Dao\Courses;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Models\Courses\CourseTeacher;

class CourseTeacherDao
{
    public function __construct()
    {

    }

    /**
     * 根据指定的专业 Major id 获取上这个课的老师的列表
     * @param $courseId
     * @param bool $simple
     * @return Collection
     */
    public function getTeachersByCourse($courseId, $simple = true){
        if($simple){
            return CourseTeacher::select(DB::raw('teacher_id as id, teacher_name as name'))
                ->where('course_id',$courseId)->get();
        }
        return CourseTeacher::where('course_id',$courseId)->get();
    }

    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function delete($id){
        return CourseTeacher::where('id',$id)->delete();
    }
}