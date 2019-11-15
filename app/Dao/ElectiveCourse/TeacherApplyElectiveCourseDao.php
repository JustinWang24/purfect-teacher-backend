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
use App\Models\ElectiveCourses\ApplyDay;
use App\Models\ElectiveCourses\ApplyGroup;
use App\Models\ElectiveCourses\ApplyTimeSlot;
use App\Models\ElectiveCourses\ApplyWeek;
use App\Models\ElectiveCourses\TeacherApplyElectiveCourse;
use App\Dao\Schools\MajorDao;
use App\Dao\Users\UserDao;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Database\Eloquent\Collection;
use Exception;
use Illuminate\Support\Facades\DB;


class TeacherApplyElectiveCourseDao
{
    use BuildFillableData;

    const STATUS_WAITING_FOR_VERIFIED = 1;
    const STATUS_WAITING_FOR_VERIFIED_TEXT = '教师申请中';
    const STATUS_WAITING_FOR_REJUCTED = 3;
    const STATUS_WAITING_FOR_REJUCTED_TEXT = '申请退回';
    const STATUS_VERIFIED = 2;
    const STATUS_VERIFIED_TEXT = '审批通过';
    const STATUS_PUBLISHED = 4;
    const STATUS_PUBLISHED_TEXT = '已经发布到课程表';

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
        return TeacherApplyElectiveCourse::find($id)->where;
        return DB::table('users')->where('name', 'John')->first();;
    }

    /**
     * 创建选修课程申请的方法
     * @param $data
     * @return IMessageBag
     */
    public function createTeacherApplyElectiveCourse($data)
    {
        if (isset($data['id']) || empty($data['id'])) {
            unset($data['id']);
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
        $applyGroups = $data['groups'];
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        $data['teacher_name'] = self::getTeacherName($data['teacher_id']);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new TeacherApplyElectiveCourse(), $data);

            // 先保存申请选修课程数据
            $apply = TeacherApplyElectiveCourse::create($fillableData);

            if ($apply) {
                self::saveTimeSlot($applyGroups, $apply->id);

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
        $id = $data['id'];
        unset($data['id']);
        $data['teacher_name'] = self::getTeacherName($data['teacher_id']);
        $applyGroups = $data['groups'];
        $data['status'] = $data['status']??self::STATUS_WAITING_FOR_VERIFIED;
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);

        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new TeacherApplyElectiveCourse(), $data);

            // 先保存申请选修课程数据
            $apply = TeacherApplyElectiveCourse::where('id', $id)->update($fillableData);

            if ($apply) {
                //先删除旧的课时安排
                ApplyGroup::where('apply_id', $id)->delete();
                // 保存课时安排
                self::saveTimeSlot($applyGroups, $id);

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
     * 课时保存方法
     * @param $applyGroups
     * @param $applyId
     */
    private function saveTimeSlot($applyGroups, $applyId)
    {
        // 保存课时安排
        if (!empty($applyGroups)) {
            DB::beginTransaction();
            try {
                foreach ($applyGroups as $applyGroup) {
                    $d = [
                        'apply_id' => $applyId,
                    ];
                    $groupObj = ApplyGroup::create($d);
                    foreach ($applyGroup as $week => $days) {
                        $f = [
                            'group_id' => $groupObj->id,
                            'week' => $week,
                        ];
                        $weekObj = ApplyWeek::create($f);
                        foreach ($days as $day => $slots) {
                            $g = [
                                'week_id' => $weekObj->id,
                                'day' => $day,
                            ];
                            $dayObj = ApplyDay::create($g);
                            foreach ($slots as $slot) {
                                $s = [
                                    'day_id' => $dayObj->id,
                                    'time_slot_id' => $slot,
                                ];
                                $slotObj = ApplyTimeSlot::create($s);
                            }
                        }
                    }
                }
                DB::commit();
            } catch (\Exception $exception) {
                dd($exception);
                DB::rollBack();
            }
        }
    }

    /**
     * 拒绝申请
     * @param $id
     * @param $content
     * @return IMessageBag
     */
    public function rejectedApply($id, $content)
    {
        return self::operateApply($id, self::STATUS_WAITING_FOR_REJUCTED);

    }

    /**
     * 批准申请
     * @param $id
     * @param $content
     * @return IMessageBag
     */
    public function approvedApply($id, $content)
    {
        return self::operateApply($id, self::STATUS_VERIFIED, $content);

    }

    /**
     * 修改为发布状态
     * @param $id
     * @return IMessageBag
     */
    public function publishedApply($id)
    {
        return self::operateApply($id, self::STATUS_PUBLISHED);
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
            $apply = TeacherApplyElectiveCourse::where('id', $id);

            if (self::STATUS_VERIFIED == $status) {
                $apply->status = self::STATUS_VERIFIED;
                $apply->reply_content .= $content;

            } elseif (in_array([self::STATUS_WAITING_FOR_REJUCTED,
                                self::STATUS_PUBLISHED], $status)) {

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
    //根据身份获取申请的状态值
    public function getDefaultStatusByRole($user)
    {
        if($user->isSchoolAdminOrAbove()) {
            return self::STATUS_VERIFIED;
        }else {
            return self::STATUS_WAITING_FOR_VERIFIED;
        }
    }

    //发布选修课到课程表中

    public function publishToCourse($applyId)
    {
        $apply = self::getApplyById($applyId);
        $data['school_id'] = $apply->school_id;
        $data['teachers'][0] = $apply->teacher_id;
        $data['majors'][0] = $apply->major_id;
        $data['code'] = $apply->code;
        $data['name'] = $apply->name;
        $data['scores'] = $apply->scores;
        $data['optional'] = 1;
        $data['year'] = $apply->year;
        $data['term'] = $apply->term;
        $data['desc'] = $apply->desc;
        $group = $apply->TimeSlot()->first();
        $weeksCollection = $group->week()->get();
        foreach ($weeksCollection as $weekCollection)
        {
            $week = $weekCollection->week;
            $daysCollection = $weekCollection->day()->get();
            foreach ($daysCollection as $dayCollection)
            {
                $day = $dayCollection->day;
                $timeSlotsCollection = $dayCollection->slot()->get();
                foreach($timeSlotsCollection as $i=>$timeSlotCollection)
                {
                    $time_slot = $timeSlotCollection->time_slot_id;
                    $arr[$week][$day][] = $time_slot;
                }
            }
        }

        $data['group'] = $arr;
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
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


}

