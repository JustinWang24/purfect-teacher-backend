<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/10/19
 * Time: 9:34 PM
 */

namespace App\Dao\ElectiveCourses;
use App\Dao\BuildFillableData;
use App\Dao\Courses\CourseDao;
use App\Dao\Schools\DepartmentDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Users\GradeUserDao;
use App\Events\User\Student\EnrollCourseEvent;
use App\Models\ElectiveCourses\ApplyCourseArrangement;
use App\Models\ElectiveCourses\ApplyCourseMajor;
use App\Models\ElectiveCourses\CourseElective;
use App\Models\ElectiveCourses\StudentEnrolledOptionalCourse;
use App\Models\ElectiveCourses\TeacherApplyElectiveCourse;
use App\Dao\Schools\MajorDao;
use App\Dao\Users\UserDao;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TeacherApplyElectiveCourseDao
{
    use BuildFillableData;
    public function __construct()
    {}

    /**
     * 根据 id 获取老师的选修课申请表
     * @param $id
     * @return TeacherApplyElectiveCourse
     */
    public function getApplyById($id)
    {
        $application = TeacherApplyElectiveCourse::where('id',$id)
            ->with('openToMajors')
            ->with('arrangements')
            ->first();

        // 一下是为了前端的表单能够显示而组件的特殊的数据结构
        $majorsId = [];
        foreach ($application->openToMajors as $openToMajor) {
            $majorsId[] = $openToMajor->major_id;
        }
        $application->majors = $majorsId;

        // 上课的时间和地点
        $schedule = [];
        foreach ($application->arrangements as $arrangement) {
            $item = [
                'weeks'=>[$arrangement->week],
                'days'=>[$arrangement->day_index],
                'timeSlots'=>[$arrangement->time_slot_id],
                'building_id'=>$arrangement->building_id,
                'building_name'=>$arrangement->building_name,
                'classroom_name'=>$arrangement->classroom_name,
                'classroom_id'=>$arrangement->classroom_id,
                'id'=>$arrangement->id,
            ];
            $schedule[] = $item;
        }
        $application->schedule = $schedule;
        //

        return $application;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteArrangementItem($id){
        return ApplyCourseArrangement::where('id',$id)->delete();
    }

    /**
     * 根据学校获取分页
     * @param $schoolId
     * @return mixed
     */
    public function getPaginatedApplications($schoolId){
        return TeacherApplyElectiveCourse::where('school_id',$schoolId)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * 获取已经审批过的申请
     * @param $id
     * @return TeacherApplyElectiveCourse|bool
     */
    public function getVerifiedApplyById($id)
    {
        $obj =  $this->getApplyById($id);
        return $obj->isVerified() ? $obj : false;
    }

    /**
     * 创建选修课程申请的方法
     * @param $data
     * @return IMessageBag
     */
    public function createTeacherApplyElectiveCourse($data)
    {
        if (!isset($data['course']['id']) || empty($data['course']['id'])) {
            unset($data['course']['id']);
        }
        /**
         * 第1组
         *      第1周，第2周，第3周
         *      星期1
         *          第7节，第8节
         *      星期3
         *          第7节
         * 第2组
         *      第6周，第8周，第9周
         *      星期4
         *          第8节
         *      星期5
         *          第7节，第8周
         * [
         * //第1组开始
         *  [
         *      1=>[
         *              1=>[7,8],
         *              3=>[7]
         *          ],
         *      2=>[
         *              1=>[7,8],
         *              3=>[7]
         *          ],
         *      3=>[
         *              1=>[7,8],
         *              3=>[7]
         *          ],
         *  ],
         * //第1组结束
         * //第2组开始
         *  [
         *      6=>[
         *              4=>[8],
         *              5=>[7,8],
         *          ],
         *      8=>[
         *              4=>[8],
         *              5=>[7,8],
         *          ],
         *      9=>[
         *              4=>[8],
         *              5=>[7,8],
         *          ],
         *  ]
         * //第2组结束
         * ]
         *
         */
        //周，星期，时间槽数据以数组方式传入
        $applyGroups = $data['schedule'];
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new TeacherApplyElectiveCourse(), $data['course']);

            // 先保存申请选修课程数据
            $apply = TeacherApplyElectiveCourse::create($fillableData);

            if ($apply) {
                //保存课时安排
                self::saveApplyCourseArrangements($applyGroups, $apply->id);
                //保存关联专业
                self::saveApplyMajor($data['course']['majors'], $apply->id, $data['course']['school_id']);

                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($apply);
            } else {
                DB::rollBack();
                $messageBag->setMessage('保存选修课程数据失败, 请联系管理员');
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }

        return $messageBag;
    }

    /**
     * 更改选修课申请
     * @param $data
     * @return MessageBag
     */
    public function updateTeacherApplyElectiveCourse($data)
    {
        $id = $data['course']['id'];
        unset($data['course']['id']);
        $data['course']['teacher_name'] = $this->getTeacherName($data['course']['teacher_id']);
        $applyGroups = $data['schedule'];
        $data['course']['status'] = $data['course']['status']??TeacherApplyElectiveCourse::STATUS_WAITING_FOR_VERIFIED;
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new TeacherApplyElectiveCourse(), $data['course']);

            // 先保存申请选修课程数据
            $apply = TeacherApplyElectiveCourse::where('id', $id)->update($fillableData);

            if ($apply) {
                //先删除旧的课时安排
                ApplyCourseArrangement::where('apply_id', $id)->delete();
                // 保存课时安排
                self::saveApplyCourseArrangements($applyGroups, $id);

                //先删除旧的关联专业
                ApplyCourseMajor::where('apply_id', $id)->delete();
                //保存新的关联专业
                self::saveApplyMajor($data['course']['majors'], $id, $data['course']['school_id']);
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($apply);
            } else {
                DB::rollBack();
                $messageBag->setMessage('无法更新选修课申请, 请联系管理员');
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    /**
     * 拒绝申请
     * @param $id
     * @param $content
     * @return IMessageBag
     */
    public function rejectedApply($id, $content)
    {
        return $this->operateApply($id, TeacherApplyElectiveCourse::STATUS_WAITING_FOR_REJECTED);
    }

    /**
     * 批准申请
     * @param $id
     * @param $content
     * @return IMessageBag
     */
    public function approvedApply($id, $content)
    {
        return $this->operateApply($id, TeacherApplyElectiveCourse::STATUS_VERIFIED, $content);
    }

    /**
     * 修改为发布状态
     * @param $id
     * @return IMessageBag
     */
    public function publishedApply($id)
    {
        return $this->operateApply($id, TeacherApplyElectiveCourse::STATUS_PUBLISHED);
    }

    /**
     * 处理申请的各种状态
     * @param $id
     * @param $status
     * @param null $content
     * @return IMessageBag
     */
    public function operateApply($id, $status, $content=null)
    {
        $msgBag = new MessageBag(JsonBuilder::CODE_ERROR, '系统错误');
        DB::beginTransaction();
        try {
            $apply = TeacherApplyElectiveCourse::where('id', $id)->first();

            if (TeacherApplyElectiveCourse::STATUS_VERIFIED == $status) {
                $apply->status = TeacherApplyElectiveCourse::STATUS_VERIFIED;
                $apply->reply_content .= $content;

            } elseif (in_array($status, [TeacherApplyElectiveCourse::STATUS_WAITING_FOR_REJECTED,TeacherApplyElectiveCourse::STATUS_PUBLISHED])) {

                $apply->status = $status;
            } else {
                throw new Exception('参数错误');
            }
            $apply->save();
            DB::commit();
            $msgBag->setCode(JsonBuilder::CODE_SUCCESS);
            $msgBag->setData($apply);
            return $msgBag;
        } catch (\Exception $exception) {
            DB::rollBack();
            $msgBag->setMessage($exception->getMessage());
            return $msgBag;
        }
    }

    /**
     * 根据 id 获取教师用户的名字
     * @param $teacherId
     * @return string|null
     */
    public function getTeacherName($teacherId)
    {
        $teacherDao = new UserDao();
        $theTeacher = $teacherDao->getUserById($teacherId);
        return $theTeacher->name ?? null;
    }

    /**
     * 根据专业id获得专业名称
     * @param $majorId
     * @return \App\Models\Schools\Major|null
     */
    public function getMajor($majorId)
    {
        $majorDao = new MajorDao();
        return $majorDao->getMajorById($majorId);
    }
    //根据身份获取申请的状态值

    /**
     * @param User $user
     * @return int
     */
    public function getDefaultStatusByRole($user)
    {
        if($user->isSchoolAdminOrAbove()) {
            return TeacherApplyElectiveCourse::STATUS_VERIFIED;
        }else {
            return TeacherApplyElectiveCourse::STATUS_WAITING_FOR_VERIFIED;
        }
    }

    //发布选修课到课程表中

    public function publishToCourse($applyId)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        //全部专业
        $applyMajors = $this->getApplyMajor($applyId);
        $majorArrs = [];
        foreach ($applyMajors->toArray() as $majorArr)
        {
            $majorArrs[] = $majorArr['major_id'];
        }
        //全部课时
        $groups = self::getApplyCourseArrangements($applyId);
        $data['group'] = $groups->toArray();
        $apply = self::getVerifiedApplyById($applyId);
        if (!$apply)
        {
            $messageBag->setMessage('没有审批过的申请不能发布！');
            return $messageBag;
        }
        $data['school_id']      = $apply->school_id;
        $data['teachers'][0]    = $apply->teacher_id;
        $data['majors']         = $majorArrs;
        $data['code']           = $apply->code;
        $data['name']           = $apply->name;
        $data['scores']         = $apply->scores;
        $data['optional']       = 1;
        $data['year']           = $apply->year;
        $data['term']           = $apply->term;
        $data['desc']           = $apply->desc;
        $data['open_num']       = $apply->open_num;
        $data['max_num']        = $apply->max_num;
        $data['start_year']     = $apply->start_year;


        DB::beginTransaction();
        try {
            //创建课程
            $courseDao = new CourseDao();
            $course = $courseDao->createCourse($data);

            //标记申请表为发布状态
            $result = self::publishedApply($applyId);
            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setData($course->getData()->id);
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    /**
     * 获取某个学校最近 3 个月的申请
     *
     * @param $schoolId
     * @param int $monthsAgo
     * @return Collection
     */
    public function getAllBySchool($schoolId, $monthsAgo = 3){
        return TeacherApplyElectiveCourse::where('school_id',$schoolId)
            ->where('created_at','>',Carbon::now()->subMonths($monthsAgo))
            ->get();
    }

    /**
     * @param $schedule
     * @param $applyId
     * @return IMessageBag
     */
    public function saveApplyCourseArrangements($schedule, $applyId)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        if (!empty($schedule)) {
            DB::beginTransaction();
            try {
                $arr = [];
                foreach ($schedule as $key => $group) {
                    foreach ($group['weeks'] as $week) {
                        foreach ($group['days'] as $day) {
                            foreach ($group['timeSlots'] as $timeSlot) {
                                $arr[$week][$day][] = $timeSlot;
                                $d = [
                                    'apply_id' => $applyId,
                                    'week' => $week,
                                    'day_index' => $day,
                                    'time_slot_id' => $timeSlot,
                                    'building_id' => $group['building_id'],
                                    'building_name' => $group['building_name'],
                                    'classroom_id' => $group['classroom_id'],
                                    'classroom_name' => $group['classroom_name'],

                                ];
                                ApplyCourseArrangement::create($d);
                            }
                        }
                    }
                }
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            } catch (\Exception $exception) {
                DB::rollBack();
                $messageBag->setMessage($exception->getMessage());
            }
        }
        return $messageBag;
    }

    /**
     * 保存申请课程的关联专业
     * @param $data
     * @param $applyId
     * @param $schoolId
     */
    public function saveApplyMajor($data, $applyId, $schoolId)
    {
        if (!empty($data))
        {
            foreach ($data as $major)
            {
                $majorObj = self::getMajor($major);
                $f = [
                    'apply_id'    => $applyId,
                    'school_id'   => $schoolId,
                    'major_id'    => $major,
                    'major_name'  => $majorObj->name,
                ];
                ApplyCourseMajor::create($f);
            }
        }
    }

    /**
     * 根据申请id获取相关选修课对应的专业
     * @param $applyId
     * @return ApplyCourseMajor|null
     */
    public function getApplyMajor($applyId)
    {
        return ApplyCourseMajor::where('apply_id', $applyId)->get();
    }

    /**
     * 根据申请id获取全部课时安排数据
     * @param $applyId
     * @return ApplyCourseArrangement|null
     */
    public function  getApplyCourseArrangements($applyId)
    {
        return ApplyCourseArrangement::where('apply_id',$applyId)->get();
    }

    /**
     * 查系或学校的相关配置获取最多能报几个选修课
     * @param User $user
     * @return mixed
     */
    public function getNumOfCanBeEnroll($user)
    {
        $schoolId = $user->getSchoolId();
        $dao = new GradeUserDao;
        $userInfo = $dao->getUserInfoByUserId($user->id);
        $major = $userInfo->major;
        $DepartmentDao = new DepartmentDao($user);
        $department = $DepartmentDao->getDepartmentById($major->department_id);
        $NumOfCanBeEnroll = $department->optional_courses_per_year;
        if (empty($NumOfCanBeEnroll)) {
            $schoolDao = new SchoolDao($user);
            $school = $schoolDao->getSchoolById($schoolId);
            $NumOfCanBeEnroll = $school->configuration->optional_courses_per_year;
        }
        return $NumOfCanBeEnroll;
    }

    /**
     * 一年之内
     *
     * @param User $user
     * @param $tableName
     * @return int
     */
    public function getTotalOfEnroll($user, $tableName)
    {
        //报名结果表中的数量
        $num1 = $this->getNumHasEnroll($user, $tableName);
        //报名中的数量
        $num2 = $this->getNumEnroll($user);
        return $num1+$num2;
    }

    /**
     * @param User $user
     * @param $tableName
     * @return int
     */
    public function getNumHasEnroll($user, $tableName)
    {
        return DB::table($tableName)->where('user_id', $user->id)->count();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getNumEnroll($user)
    {
        return StudentEnrolledOptionalCourse::where('user_id', $user->id)->count();
    }


    /**
     * 判断报名报是否存在，不存在则创建
     * @param $tableName
     * @return bool
     */
    public function createEnrollTable($tableName)
    {
        $sql = '
        CREATE TABLE IF NOT EXISTS '.$tableName.' (
          `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
          `course_id` int(10) unsigned DEFAULT NULL COMMENT \'课程的 ID\',
          `school_id` int(10) unsigned DEFAULT NULL COMMENT \'学校的 ID\',
          `teacher_id` int(10) unsigned DEFAULT NULL COMMENT \'老师ID\',
          `user_id` int(10) unsigned DEFAULT NULL COMMENT \'学生的 ID\',
          `status` smallint(5) unsigned DEFAULT \'0\' COMMENT \'0 申请中、1 开班成功申请成功、 2 开班成功申请失败\',
          `created_at` timestamp NULL DEFAULT NULL,
          `updated_at` timestamp NULL DEFAULT NULL,
          PRIMARY KEY (`id`),
          KEY `idx_course_id` (`course_id`),
          KEY `idx_school_id` (`school_id`),
          KEY `idx_user_id` (`user_id`),
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ';
        if ( ! Schema::hasTable($tableName)) {
            return DB::statement($sql);
        } else {
            return true;
        }
    }

    /**
     * 查询课程是否名额已满
     * @param $courseId
     * @return bool
     */
    public function quotaIsFull($courseId)
    {
        return CourseElective::where('course_id',$courseId)->first()->status == CourseElective::STATUS_ISFULL;
    }

    /**
     * 报名
     * @param $courseId
     * @param $userId
     * @param $teacherId
     * @param $schoolId
     * @return StudentEnrolledOptionalCourse
     */
    public function enroll($courseId, $userId, $teacherId, $schoolId)
    {
        $d = [
            'course_id' => $courseId,
            'teacher_id'=> $teacherId,
            'user_id'   => $userId,
            'school_id' => $schoolId,
        ];
        return StudentEnrolledOptionalCourse::create($d);
    }

    /**
     * 处理报名结果表，查询报名总数与max_num比较，
     * 修改course_elective表的状态，删除报名表中的数据,
     * select其中前max_num条数据，写入报名结果表，
     * @param $maxNum
     * @param $courseId
     * @param $tableName
     * @return bool
     */
    public function operateEnrollResult($maxNum, $courseId, $tableName)
    {
        //创建报名结果表
        $this->createEnrollTable($tableName);
        if (self::quotaIsFull($courseId)) return true;
        if (self::getEnrolledTotalForCourses($courseId) >= $maxNum)
        {
            DB::beginTransaction();
            try {
                //先修改course_elective表的状态，让其他用户不能报名
                $updateNum = CourseElective::where('course_id', $courseId)
                                ->update(['status' => CourseElective::STATUS_ISFULL]);
                if ($updateNum==1)
                {
                    $result = StudentEnrolledOptionalCourse::where('course_id', $courseId)
                        ->orderBy('id', 'ASC')->limit($maxNum)->get();
                    $i = 0;
                    foreach($result as $rowObj)
                    {

                        $d = [
                            'course_id'     => $rowObj->course_id,
                            'teacher_id'    => $rowObj->teacher_id,
                            'user_id'       => $rowObj->user_id,
                            'school_id'     => $rowObj->school_id,
                            'created_at'    => Carbon::now(),
                            'updated_at'    => Carbon::now(),
                        ];
                        DB::table($tableName)->insert($d) && $i++;
                    }
                    if ($i !== $maxNum) {
                        throw new Exception('报名表与结果表不一致');
                    }
                    //删除报名表记录
                    StudentEnrolledOptionalCourse::where('course_id', $courseId)->delete();
                }
                DB::commit();
                return true;
            } catch (\Exception $exception) {
                DB::rollBack();
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 取消报名，加共享锁，当提交或回滚后解除锁定
     * @param $userId
     * @param $courseId
     */
    public function cancelEnroll($userId, $courseId)
    {
        DB::beginTransaction();
        try {
            StudentEnrolledOptionalCourse::where('user_id', $userId)
                ->where('course_id', $courseId)->sharedLock()->delete();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }
    }

    /**
     * 获取某课程的报名总人数
     * @param $courseId
     * @return int
     */
    public function getEnrolledTotalForCourses($courseId)
    {
        return StudentEnrolledOptionalCourse::where('course_id', $courseId)->count();
    }

    /**
     * 获得用户在报名结果表或报名表中某课程的排名
     * @param $user
     * @param $courseId
     * @param $tableName
     * @return bool|int
     */
    public function getRanking($user, $courseId, $tableName)
    {
        $result = DB::table($tableName)->where('course_id', $courseId)->get();
        foreach ($result as $key => $item) {
            if ($item->user_id == $user->id)
            {
                return ++$key;
            }
        }
        return false;
    }

    /**
     * 获得用户在报名结果表或报名表中某课程的记录
     * @param $user
     * @param $courseId
     * @param $tableName
     * @return \Illuminate\Support\Collection
     */
    public function getResultEnrollRow($user, $courseId, $tableName)
    {
        return DB::table($tableName)->where('course_id', $courseId)
            ->where('user_id', $user->id)->get()->first();
    }

    /**
     * 通知学生
     * @param $courseId
     * @param $tableName
     */
    public function notifyStudent($courseId, $tableName)
    {
        $rows = DB::table($tableName)->where('course_id', $courseId)->get();
        foreach ($rows as $row)
        {
            $row = json_decode(json_encode($row), true);

            // Todo: 为什么要在循环中抛出事件???
            event(new EnrollCourseEvent($row));
        }
    }
}

