<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 31/10/19
 * Time: 12:33 PM
 */

namespace App\Dao\Courses;
use App\Models\Course;
use Illuminate\Support\Facades\DB;

class CourseArrangementDao
{
    private $course;
    private $tableName = 'course_arrangements';
    /**
     * CourseArrangementDao constructor.
     * @param Course $course
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * @param $weeks
     * @param $days
     * @param $timeSlotIds
     * @return bool
     */
    public function save($weeks, $days, $timeSlotIds){
        $this->deleteByCourseId($this->course->id);
        $rows = [];
        foreach ($weeks as $week) {
            foreach ($days as $day) {
                foreach ($timeSlotIds as $timeSlotId) {
                    $rows[] = [
                        'course_id'=>$this->course->id,
                        'week'=>$week,
                        'day_index'=>$day,
                        'time_slot_id'=>$timeSlotId
                    ];
                }
            }
        }
        return DB::table($this->tableName)->insert($rows);
    }

    /**
     * 按指定的课程 id 删除课程的安排
     * @param $courseId
     * @return int
     */
    public function deleteByCourseId($courseId){
        return DB::table('course_arrangements')
            ->where('course_id', $courseId)
            ->delete();
    }
}