<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/10/19
 * Time: 9:34 PM
 */

namespace App\Dao\Courses;
use App\Dao\Schools\MajorDao;
use App\Dao\Users\UserDao;
use App\Models\Course;
use App\Models\Courses\CourseArrangement;
use App\Models\Courses\CourseMajor;
use App\Models\Courses\CourseTeacher;
use App\Models\ElectiveCourses\CourseElective;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use App\Dao\BuildFillableData;

class CourseDao
{
    use BuildFillableData;
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

    /**
     * @param $idOrUuid
     * @return Course
     */
    public function getCourseByIdOrUuid($idOrUuid){
        if(strlen($idOrUuid) > 32){
            return $this->getCourseByUuid($idOrUuid);
        }
        else{
            return $this->getCourseById($idOrUuid);
        }
    }

    /**
     * @param $uuid
     * @return Course
     */
    public function getCourseByUuid($uuid){
        return Course::where('uuid',$uuid)->first();
    }

    /**
     * @param $id
     * @return Course
     */
    public function getCourseById($id){
        return Course::find($id);
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
     * @return IMessageBag
     */
    public function updateCourse($data){
        $id = $data['id'];
        unset($data['id']);

        $teachersId = $data['teachers'];
        $majorsId = $data['majors'];

        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        DB::beginTransaction();
        try{
            // 先更新课程数据
            $filledData = $this->getFillableData(new Course(), $data);
            $course = Course::where('id',$id)->update($filledData);

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
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            }
            else{
                DB::rollBack();
                $messageBag->setMessage('无法更新课程信息, 请联系管理员');
            }

        }catch (\Exception $exception){
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }

        return $messageBag;
    }

    /**
     * 创建课程的方法
     * @param $data
     * @return IMessageBag
     */
    public function createCourse($data){
        if(isset($data['id']) || empty($data['id'])){
            unset($data['id']);
        }
        $teachersId = $data['teachers'];
        $majorsId = $data['majors'];

        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        DB::beginTransaction();
        try{
            $data['uuid'] = Uuid::uuid4()->toString();
            $fillableData = $this->getFillableData(new Course(),$data);

            // 先保存课程数据
            $course = Course::create($fillableData);

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
                    //添加course_electives表的关联数据
                    $this->_saveCourseElective($course, $data);

                }

                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($course);
            }
            else{
                DB::rollBack();
                $messageBag->setMessage('保存课程数据失败, 请联系管理员');
            }

        }catch (\Exception $exception){
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }

        return $messageBag;
    }

    /**
     * Course
     * @param $course
     * @param $data
     * @return bool
     */
/*    private function _saveCourseArrangement($course, $data){
        $days = $data['dayIndexes'];
        $timeSlotIds = $data['timeSlots'];
        $weeks = $data['weekNumbers'];
        $arrangement = new CourseArrangementDao($course);
        return $arrangement->save($weeks, $days, $timeSlotIds);
    }*/

    /**
     * 根据学校的 ID 获取课程
     * @param $schoolId
     * @param $pageNumber
     * @param $pageSize
     * @return array|Collection
     */
    public function getCoursesBySchoolId($schoolId, $pageNumber=0, $pageSize=20){
        $courses = Course::where('school_id',$schoolId)
            ->skip($pageNumber * $pageSize)
            ->take($pageSize)
            ->get();
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
            $item['arrangements'] = [];
            if($course->optional){
                // 是选修课
                $item['arrangements'] = $course->arrangements;
            }

            $data[] = $item;
        }
        return $data;
    }


    /**
     * 通过idArr查询课程列表
     * @param $idArr
     * @param $simple
     * @return mixed
     */
    public function getCoursesByIdArr($idArr,$simple = true) {
        $field = '*';
        if($simple) {
            $field = ['id', 'code', 'name', 'year', 'term', 'scores'];
        }
        $result = Course::whereIn('id',$idArr)->with('courseTextbooks.textbook')->select($field)->get();
        return $result;
    }

    /*
     *
            [
                1=>[1=>[7,8],3=>[7]],
                2=>[1=>[7,8],3=>[7]],
                3=>[1=>[7,8],3=>[7]],
            ],
     *
     */
    private function _saveCourseArrangement($course, $data){

        // 保存课时安排
        if (!empty($data['group'])) {
            $times = $data['group'];
            DB::beginTransaction();
            try {
                foreach ($times as $time) {
                    $d = [
                        'course_id'     => $course->id,
                        'week'          => $time['week'],
                        'day_index'     => $time['day_index'],
                        'time_slot_id'  => $time['time_slot_id'],
                        'building_id'   => $time['building_id'],
                        'building_name' => $time['building_name'],
                        'classroom_id'  => $time['classroom_id'],
                        'classroom_name'=> $time['classroom_name'],
                    ];
                    $courseArrangement = CourseArrangement::create($d);
                }
                DB::commit();
            } catch (\Exception $exception) {
                DB::rollBack();
            }
        }
    }

    /**
     * 保存course_electives表数据
     * @param $course
     * @param $data
     */
    private function _saveCourseElective($course, $data)
    {
        DB::beginTransaction();
        try {
            $d = [
                'course_id'     => $course->id,
                'open_num'      => $data['open_num'],
                'max_num'       => $data['max_num'],
                'start_year'    => $data['start_year'],
            ];
            CourseElective::create($d);
            DB::commit();
        } catch (\Exception $exception) {
            dd($exception);
            DB::rollBack();
        }

    }

}
