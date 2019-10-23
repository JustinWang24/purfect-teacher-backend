<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/10/19
 * Time: 9:34 PM
 */

namespace App\Dao\Courses;
use App\Dao\Schools\MajorDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Dao\Users\UserDao;
use App\Models\Course;
use App\Models\Courses\CourseMajor;
use App\Models\Courses\CourseTeacher;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CourseDao
{
    protected $fields = [
        'code','name','uuid',
        'scores',
        'optional',
        'year',
        'term',
        'desc',
    ];
    public function __construct()
    {

    }

    public function createCourse($data){
        $result = true;
        if(isset($data['id']) || empty($data['id'])){
            unset($data['id']);
        }
        $teachersId = $data['teachers'];
        unset($data['teachers']);
        $majorsId = $data['majors'];
        unset($data['majors']);

        DB::beginTransaction();
        try{
            $data['uuid'] = Uuid::uuid4()->toString();
            // 先保存课程数据
            $course = Course::create($data);

            if($course){
                // 保存授课老师
                if(!empty($teachersId)){
                    $teacherDao = new TeacherProfileDao();
                    foreach ($teachersId as $teacherId) {
                        $theTeacher = $teacherDao->getTeacherProfileByTeacherIdOrUuid($teacherId);
                        $data = [
                            'course_id'=>$course->id,
                            'course_code'=>$course->code,
                            'teacher_id'=>$teacherId,
                            'school_id'=>$data['school_id'],
                            'teacher_name'=>$theTeacher->name ?? 'n.a',
                            'course_name'=>$course->name
                        ];
                        CourseTeacher::create($data);
                    }
                }
                // 保存课程所关联的专业
                if(!empty($majorsId)){
                    $majorDao = new MajorDao();
                    foreach ($majorsId as $majorId) {
                        $theMajor = $majorDao->getMajorById($majorId);
                        $data = [
                            'course_id'=>$course->id,
                            'course_code'=>$course->code,
                            'major_id'=>$majorId,
                            'school_id'=>$data['school_id'],
                            'major_name'=>$theMajor->name ?? 'n.a',
                            'course_name'=>$course->name
                        ];
                        CourseMajor::create($data);
                    }
                }
                DB::commit();
            }
            else{
                DB::rollBack();
                $result = false;
            }

        }catch (\Exception $exception){
            DB::rollBack();
            $result = false;
            dump($exception->getMessage());
        }

        return $result;
    }

    /**
     * 根据学校的 ID 获取课程
     * @param $schoolId
     * @return Collection
     */
    public function getCoursesBySchoolId($schoolId){
        return Course::select($this->fields)->where('school_id',$schoolId)->get();
    }
}