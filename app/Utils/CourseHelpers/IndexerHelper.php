<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace App\Utils\CourseHelpers;


use App\Dao\Timetable\TimetableItemDao;
use App\Models\Course;
use App\Models\Schools\Grade;
use App\User;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;

class IndexerHelper
{
    /**
     * @var Course $course
     */
    private $course;

    public function __construct(Course $course)
    {
        $this->course = $course;
    }

    /**
     * @param User $teacher
     * @param Carbon|null $date
     * @return \Illuminate\Support\Collection
     */
    public function getCurrentIndexByTeacher(User $teacher, Carbon $date = null){
        $timetableItemDao = new TimetableItemDao();
        $time = GradeAndYearUtil::GetYearAndTerm($date??now(GradeAndYearUtil::TIMEZONE_CN));
        $items = $timetableItemDao->getItemByGradeAndTeacherAndYear(
            $this->course->id, $teacher->id, $time['year'], $time['term']
        );
        return $items;
    }
}