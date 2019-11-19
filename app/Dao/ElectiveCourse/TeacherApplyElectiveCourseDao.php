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
use App\Models\ElectiveCourses\ApplyCourseArrangement;
use App\Models\ElectiveCourses\ApplyCourseMajor;
use App\Models\ElectiveCourses\ApplyDay;
use App\Models\ElectiveCourses\ApplyGroup;
use App\Models\ElectiveCourses\ApplyTimeSlot;
use App\Models\ElectiveCourses\ApplyWeek;
use App\Models\ElectiveCourses\TeacherApplyElectiveCourse;
use App\Dao\Schools\MajorDao;
use App\Dao\Users\UserDao;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use Illuminate\Support\Facades\DB;


class TeacherApplyElectiveCourseDao
{
    use BuildFillableData;
    public function __construct()
    {
    }


    public function getApplyById($id)
    {
        return TeacherApplyElectiveCourse::find($id);
    }
    //获取已经审批过的申请
    public function getVerifiedApplyById($id)
    {
        $obj =  TeacherApplyElectiveCourse::find($id);
        if(TeacherApplyElectiveCourse::STATUS_VERIFIED == $obj->status)
        {
            return $obj;
        } else {
            return false;
        }

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
        $data['course']['teacher_name'] = self::getTeacherName($data['course']['teacher_id']);
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
        return self::operateApply($id, TeacherApplyElectiveCourse::STATUS_WAITING_FOR_REJECTED);

    }

    /**
     * 批准申请
     * @param $id
     * @param $content
     * @return IMessageBag
     */
    public function approvedApply($id, $content)
    {
        return self::operateApply($id, TeacherApplyElectiveCourse::STATUS_VERIFIED, $content);

    }

    /**
     * 修改为发布状态
     * @param $id
     * @return IMessageBag
     */
    public function publishedApply($id)
    {
        return self::operateApply($id, TeacherApplyElectiveCourse::STATUS_PUBLISHED);
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
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
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
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setData($apply);
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }
    //根据教师id获取教师姓名
    public function getTeacherName($teacherId)
    {
        $teacherDao = new UserDao();
        $theTeacher = $teacherDao->getUserById($teacherId);
        return $theTeacher->name;
    }
    //根据专业id获得专业名称
    public function getMajor($majorId)
    {
        $majorDao = new MajorDao();
        $major = $majorDao->getMajorById($majorId);
        return $major;
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
        $applyMajors = self::getApplyMjor($applyId);
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
        $data['school_id'] = $apply->school_id;
        $data['teachers'][0] = $apply->teacher_id;
        $data['majors'] = $majorArrs;
        $data['code'] = $apply->code;
        $data['name'] = $apply->name;
        $data['scores'] = $apply->scores;
        $data['optional'] = 1;
        $data['year'] = $apply->year;
        $data['term'] = $apply->term;
        $data['desc'] = $apply->desc;
        $data['open_num'] = $apply->open_num;
        $data['max_num'] = $apply->max_num;


        DB::beginTransaction();
        try {
            //创建课程
            $courseDao = new CourseDao();
            $courseDao->createCourse($data);
            //标记申请表为发布状态
            self::publishedApply($applyId);
            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
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
            ->where('created_at','>',Carbon::now()->subMonths(3))
            ->get();
    }


    public function saveApplyCourseArrangements($schedule, $applyId)
    {
        if (!empty($schedule)) {
            $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
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
                dd($exception);
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
     * @return mixed
     */
    public function getApplyMjor($applyId)
    {
        return ApplyCourseMajor::where('apply_id', $applyId)->get();
    }

    /**
     * 根据申请id获取全部课时安排数据
     * @param $applyId
     * @return mixed
     */
    public function  getApplyCourseArrangements($applyId)
    {
        return ApplyCourseArrangement::where('apply_id',$applyId)->get();
    }
}

