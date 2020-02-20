<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace Tests\Unit\Utils;

use App\Models\Course;
use App\Models\Courses\Lecture;
use App\User;
use App\Utils\Files\UploadFiles;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Tests\TestCase;

class UploadFilesHelperTest extends TestCase
{
    public function testItCanBuildStudentHomeworkPathCorrectly(){
        $helper = new UploadFiles();
        $student = User::find(168579);
        $course = Course::find(45);
        $lecture = Lecture::find(10);
        $lectureIdx = 1;
        $yat = GradeAndYearUtil::GetYearAndTerm(Carbon::now());
        $year = $yat['year'];
        $term = $yat['term'];

        $path = $helper->buildStudentHomeworkPath($course, $lecture, $lectureIdx, $student);

        // 构建的保存学生某个课程的作业的路径
        $findMy = $student->uuid . DIRECTORY_SEPARATOR .
            $year . DIRECTORY_SEPARATOR .
            $term . DIRECTORY_SEPARATOR .
            $course->id . DIRECTORY_SEPARATOR .
            $lecture->id . DIRECTORY_SEPARATOR .
            $lectureIdx;

        dump($path);

        $this->assertTrue(strpos($path['store_path'], 'public/homework/'.$findMy) > -1);
    }
}