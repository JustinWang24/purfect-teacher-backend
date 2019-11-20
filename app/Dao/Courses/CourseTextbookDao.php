<?php

namespace App\Dao\Courses;

use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use App\Models\Courses\CourseTextbook;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CourseTextbookDao
{

    /**
     * 创建
     * @param $data
     * @return mixed
     */
    public function create($data) {
        return CourseTextbook::create($data);
    }


    /**
     * 添加课程教材关联
     * @param $courseId
     * @param $schoolId
     * @param $textbookIdArr
     * @return MessageBag
     */
    public function createCourseTextbook($courseId, $schoolId, $textbookIdArr) {
        $data = [];
        $dateTime = Carbon::now()->toDateTimeString();

        foreach ($textbookIdArr as $key => $value) {
            $data[] = [
                'course_id' => $courseId,
                'school_id' => $schoolId,
                'textbook_id' => $value,
                'created_at' => $dateTime,
                'updated_at' => $dateTime,
            ];
        }

        $re = DB::table('course_textbooks')->insert($data);

        if($re){
            return new MessageBag(JsonBuilder::CODE_SUCCESS,'添加成功');
        } else {
            return new MessageBag(JsonBuilder::CODE_ERROR,'添加失败');
        }
    }

}
