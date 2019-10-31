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
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class CourseDao
{
    protected $fields = [
        'code','name','uuid','id',
        'scores',
        'optional',
        'year',
        'term',
        'desc',
    ];
    public function __construct()
    {

    }

    public function getCourseByUuid($uuid){
        return Course::where('uuid',$uuid)->first();
    }

    /**
     * 根据 uuid 删除课程所有数据
     * @param $uuid
     * @return bool
     */
    public function deleteCourseByUuid($uuid){
        $result = true;
        $course = $this->getCourseByUuid($uuid);
        if($course){
            DB::beginTransaction();
            try{
                $id = $course->id;
                $dao = new CourseArrangementDao($course);
                $dao->deleteByCourseId($id);
                CourseTeacher::where('course_id',$id)->delete();
                CourseMajor::where('course_id',$id)->delete();
                $course->delete();
                DB::commit();
            }catch (\Exception $exception){
                DB::rollBack();
                $result = false;
            }
        }
        return $result;
    }

    /**
     * @param $data
     * @return bool
     */
    public function updateCourse($data){
        $id = $data['id'];
        unset($data['id']);

        $teachersId = $data['teachers'];
        unset($data['teachers']);
        $majorsId = $data['majors'];
        unset($data['majors']);

        DB::beginTransaction();
        try{
            // 先更新课程数据
            $course = Course::where('id',$id)->update($data);

            if($course){
                // 删除所有的授课老师
                CourseTeacher::where('course_id',$id)->delete();
                // 保存授课老师
                if(!empty($teachersId)){
                    $userDao = new UserDao();
                    foreach ($teachersId as $teacherId) {
                        // 先检查当前这条
                        $theTeacher = $userDao->getUserById($teacherId);
                        $d = [
                            'course_id'=>$id,
                            'course_code'=>$data['code'],
                            'teacher_id'=>$teacherId,
                            'school_id'=>$data['school_id'],
                            'teacher_name'=>$theTeacher->name ?? 'n.a',
                            'course_name'=>$data['name']
                        ];
                        CourseTeacher::create($d);
                    }
                }

                // 保存课程所关联的专业
                // 删除所有的关联专业
                CourseMajor::where('course_id',$id)->delete();
                if(!empty($majorsId)){
                    $majorDao = new MajorDao();
                    foreach ($majorsId as $majorId) {
                        $theMajor = $majorDao->getMajorById($majorId);
                        $d = [
                            'course_id'=>$id,
                            'course_code'=>$data['code'],
                            'major_id'=>$majorId,
                            'school_id'=>$data['school_id'],
                            'major_name'=>$theMajor->name ?? 'n.a',
                            'course_name'=>$data['name']
                        ];
                        CourseMajor::create($d);
                    }
                }

                // 检查是选修课还是必修课, 如果是选修课, 则需要保留选修课的上课时间信息, 并保存到单独的记录表中
                if(intval($data['optional']) === 1){
                    // 是选修课
                    $this->_saveCourseArrangement($course, $data);
                }

                DB::commit();
                $result = true;
            }
            else{
                DB::rollBack();
                $result = false;
            }

        }catch (\Exception $exception){
            DB::rollBack();
            $result = false;
        }

        return $result;
    }

    /**
     * 创建课程的方法
     * @param $data
     * @return Course|boolean
     */
    public function createCourse($data){
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
                    $teacherDao = new UserDao();
                    foreach ($teachersId as $teacherId) {
                        $theTeacher = $teacherDao->getUserById($teacherId);
                        $d = [
                            'course_id'=>$course->id,
                            'course_code'=>$course->code,
                            'teacher_id'=>$teacherId,
                            'school_id'=>$data['school_id'],
                            'teacher_name'=>$theTeacher->name ?? 'n.a',
                            'course_name'=>$course->name
                        ];
                        CourseTeacher::create($d);
                    }
                }
                // 保存课程所关联的专业
                if(!empty($majorsId)){
                    $majorDao = new MajorDao();
                    foreach ($majorsId as $majorId) {
                        $theMajor = $majorDao->getMajorById($majorId);
                        $d = [
                            'course_id'=>$course->id,
                            'course_code'=>$course->code,
                            'major_id'=>$majorId,
                            'school_id'=>$data['school_id'],
                            'major_name'=>$theMajor->name ?? 'n.a',
                            'course_name'=>$course->name
                        ];
                        CourseMajor::create($d);
                    }
                }

                // 检查是选修课还是必修课, 如果是选修课, 则需要保留选修课的上课时间信息, 并保存到单独的记录表中
                if(intval($data['optional']) === 1){
                    // 是选修课
                    $this->_saveCourseArrangement($course, $data);
                }

                DB::commit();
                $result = $course;
            }
            else{
                DB::rollBack();
                $result = false;
            }

        }catch (\Exception $exception){
            DB::rollBack();
            $result = false;
        }

        return $result;
    }

    /**
     * Course
     * @param $course
     * @param $data
     * @return bool
     */
    private function _saveCourseArrangement($course, $data){
        $days = $data['dayIndexes'];
        $timeSlotIds = $data['timeSlots'];
        $weeks = $data['weekNumbers'];
        $arrangement = new CourseArrangementDao($course);
        return $arrangement->save($weeks, $days, $timeSlotIds);
    }

    /**
     * 根据学校的 ID 获取课程
     * @param $schoolId
     * @return array
     */
    public function getCoursesBySchoolId($schoolId){
        $courses = Course::where('school_id',$schoolId)->get();
        $data = [];
        foreach ($courses as $course) {
            /**
             * @var Course $course
             */
            $item = [];
            foreach ($this->fields as $field) {
                $item[$field] = $course->$field;
            }
            $item['teachers'] = $course->teachers;
            $item['majors'] = $course->majors;
            $data[] = $item;
        }
        return $data;
    }
}