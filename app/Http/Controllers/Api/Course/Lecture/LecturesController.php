<?php
/**
 * Created by https://yue.dev
 * Author: Justin Wang
 * Email: hi@yue.dev
 */

namespace App\Http\Controllers\Api\Course\Lecture;
use App\Dao\Courses\CourseDao;
use App\Http\Controllers\Controller;
use App\Utils\Files\UploadFiles;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Dao\Courses\Lectures\LectureDao;
use App\Utils\JsonBuilder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LecturesController extends Controller
{

    /**
     * 加载指定课节的课件
     * @param Request $request
     * @return string
     */
    public function load_lecture(Request $request){
        $teacher = $request->user();
        if($teacher){
            $dao = new LectureDao();
            $materials = $dao->getLecturesByCourseAndTeacherAndIndex(
                $request->get('course'),
                $teacher->id,
                $request->get('index')
            );
            return JsonBuilder::Success(['lecture'=>$materials]);
        }
        return JsonBuilder::Error('无效的用户信息');
    }

    /**
     * 加载某个课节的材料
     * @param Request $request
     * @return string
     */
    public function load_lecture_materials(Request $request){
        $dao = new LectureDao();
        return JsonBuilder::Success(['materials'=>$dao->getLectureMaterials($request->get('lecture_id'))]);
    }

    /**
     * 学生加载自己某节课的作业
     * @param Request $request
     * @return string
     */
    public function load_student_homework(Request $request){
        $courseId = $request->get('course_id');
        $idx= $request->get('idx');
        $student = $request->user();
        // 获取当前时间
        $yat = GradeAndYearUtil::GetYearAndTerm(Carbon::now());
        if($student){
            $homeworks = (new LectureDao())->getHomeworkByStudentAndLectureAndYear($student->id, $courseId, $idx, $yat['year']);
            return JsonBuilder::Success(['homeworks'=>$homeworks]);
        }
        return JsonBuilder::Error('无效的用户信息');
    }

    /**
     * 保存学生作业的接口
     * @param Request $request
     * @return string
     */
    public function save_homework(Request $request){
        /**
         * 保存学生作业的处理
         * 首先，如果提交了附件文档，那么首先保存附件文档到学生的云盘中，保存的路径为
         * 学生自己的根目录/homework/year/term/course_id/lecture_index/
         *
         * 保存附件之后，得到附件的url，然后在插入的homeworks表格中
         */
        $bodyObject = json_decode($request->get('body'));
        $course = (new CourseDao())->getCourseById($bodyObject->course_id);
        $lectureDao = new LectureDao();
        $lecture = $lectureDao->getLectureById($bodyObject->lecture_id);
        $student = $request->user('api');
        $uploadFileHelper = new UploadFiles();
        $yat = GradeAndYearUtil::GetYearAndTerm(Carbon::now());
        $year = $yat['year'];
        $term = $yat['term'];

        $path = $uploadFileHelper->buildStudentHomeworkPath($course, $lecture, $bodyObject->idx, $student, $year, $term);

        if($request->has('file')){
            /**
             * @var \Illuminate\Http\UploadedFile $upload
             */
            $upload = $request->file;
            $upload->storeAs($path['store_path'],$upload->getClientOriginalName()); // 保存文件
            $homework = $lectureDao->saveHomework([
                'lecture_id'=>$lecture->id,
                'year'=>$year,
                'course_id'=>$course->id,
                'student_id'=>$student->id,
                'student_name'=>$student->name,
                'content'=>empty($bodyObject->content) ? $upload->getClientOriginalName() : $bodyObject->content,
                'url'=>$path['url_path'].DIRECTORY_SEPARATOR.$upload->getClientOriginalName(),
                'idx'=>$bodyObject->idx,
                'comment'=>'',
                'score'=>0,
            ]);
            if($homework){
                return JsonBuilder::Success();
            }else{
                return JsonBuilder::Error('数据库错误');
            }
        }
        return JsonBuilder::Error('没有提交作业的附件');
    }

    /**
     * @param Request $request
     * @return string
     */
    public function delete_homework(Request $request){
        $deleted = (new LectureDao())->deleteHomework($request->get('homework_id'));
        return $deleted ? JsonBuilder::Success() : JsonBuilder::Error();
    }
}