<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/10/19
 * Time: 9:34 PM
 */

namespace App\Dao\ElectiveCourses;
use App\Dao\BuildFillableData;
use App\Models\ElectiveCourses\ApplyDayIndex;
use App\Models\ElectiveCourses\ApplyGroup;
use App\Models\ElectiveCourses\ApplyTimeSlot;
use App\Models\ElectiveCourses\ApplyWeek;
use App\Models\ElectiveCourses\TeacherApplyElectiveCourse;
use App\Models\ElectiveCourses\TeacherApplyElectiveCoursesTimeSlot;
use App\Dao\Schools\MajorDao;
use App\Dao\Users\UserDao;
//use App\Models\Course;
//use App\Models\Courses\CourseMajor;
//use App\Models\Courses\CourseTeacher;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
//use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
//use Ramsey\Uuid\Uuid;
//use App\Dao\BuildFillableData;

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
        $applyGroups = $data['groups'];
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
                                'day_index' => $day,
                            ];
                            $dayObj = ApplyDayIndex::create($g);
                            foreach ($slots as $slot) {
                                $s = [
                                    'day_index_id' => $dayObj->id,
                                    'time_slot_id' => $slot,
                                ];
                                $slotObj = ApplyTimeSlot::create($s);
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
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
        $status = self::STATUS_WAITING_FOR_REJUCTED;
        return self::operateApply($id, $status);

    }

    /**
     * 批准申请
     * @param $id
     * @param $content
     * @return IMessageBag
     */
    public function approvedApply($id, $content)
    {
        $status = self::STATUS_VERIFIED;
        return self::operateApply($id, $status, $content);

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
}
