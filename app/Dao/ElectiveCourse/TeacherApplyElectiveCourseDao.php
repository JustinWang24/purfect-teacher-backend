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
use App\Dao\Courses\CourseTeacherDao;
use App\Dao\Schools\BuildingDao;
use App\Dao\Schools\DepartmentDao;
use App\Dao\Schools\RoomDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Users\GradeUserDao;
use App\Events\SystemNotification\ApproveElectiveCourseEvent;
use App\Events\SystemNotification\ApproveElectiveTeacherEvent;
use App\Events\User\Student\EnrollCourseEvent;
use App\Models\Course;
use App\Models\ElectiveCourses\ApplyCourseArrangement;
use App\Models\ElectiveCourses\ApplyCourseMajor;
use App\Models\ElectiveCourses\CourseElective;
use App\Models\ElectiveCourses\StudentEnrolledOptionalCourse;
use App\Models\ElectiveCourses\TeacherApplyElectiveCourse;
use App\Dao\Schools\MajorDao;
use App\Dao\Users\UserDao;
use App\Models\Schools\SchoolConfiguration;
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
    {
    }

    /**
     * 根据 id 获取老师的选修课申请表
     * @param $id
     * @return TeacherApplyElectiveCourse
     */
    public function getApplyById($id)
    {
        $application = TeacherApplyElectiveCourse::where('id', $id)
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
                'weeks' => [$arrangement->week],
                'days' => [$arrangement->day_index],
                'timeSlots' => [$arrangement->time_slot_id],
                'building_id' => $arrangement->building_id,
                'building_name' => $arrangement->building_name,
                'classroom_name' => $arrangement->classroom_name,
                'classroom_id' => $arrangement->classroom_id,
                'id' => $arrangement->id,
            ];
            $schedule[] = $item;
        }
        $application->schedule = $schedule;
        //

        return $application;
    }

    /**
     * 检测报名课程冲突时间 false=不冲突
     * @param $enrollCourseId
     * @param $userId
     */
    public function checkTimeConflictByUserId($enrollCourseId, $userId) {
        $courseDao =  new CourseDao;
        $enrollCourse = $courseDao->getCourseById($enrollCourseId);
        $term = $enrollCourse->term;
        $year = $enrollCourse->courseElective->start_year;
        $valList = [];
        foreach ($enrollCourse->arrangements as $arrangement) {
          $valList[] = 'w_' . $arrangement->week . '_d_' . $arrangement->day_index . '_t_' . $arrangement->time_slot_id;
        }
        if (!$valList) {
            return false;
        }
        $enrollList = StudentEnrolledOptionalCourse::with('course')->where('user_id', $userId)->get();
        if ($enrollList) {
            foreach ($enrollList as $enroll) {
                if ($enroll->course && $enroll->course->term == $term && $enroll->course->courseElective->start_year == $year) {
                    foreach ($enroll->course->arrangements as $arrangement1) {
                        $checkKey = 'w_' . $arrangement1->week . '_d_' . $arrangement1->day_index . '_t_' . $arrangement1->time_slot_id;
                        if (in_array($checkKey, $valList)) {
                            return ['week' => $arrangement1->week, 'day' => $arrangement1->day_index, 'time' => $arrangement1->time_slot_id];
                        }
                    }
                }
            }
        }

        $tableName = 'student_enrolled_optional_courses_'.$year.'_'.$term;
        $enrollList2 = DB::table($tableName)->where('user_id', $userId)->get();
        if ($enrollList2) {
            foreach ($enrollList2 as $enroll2) {
                $encourse = $courseDao->getCourseById($enroll2->course_id);
                foreach ($encourse->arrangements as $arrangement2) {
                  $checkKey = 'w_' . $arrangement2->week . '_d_' . $arrangement2->day_index . '_t_' . $arrangement2->time_slot_id;
                  if (in_array($checkKey, $valList)) {
                      return ['week' => $arrangement2->week, 'day' => $arrangement2->day_index, 'time' => $arrangement2->time_slot_id];
                  }
                }
            }
        }

        //@TODO 验证学生课表
        return false;
    }


    /**
     * 检测教师冲突时间 false=不冲突
     * @param $schedule
     * @param $year
     * @param $term
     * @param $teacherId
     * @return array|bool
     */
    public function checkTimeConflictByTeacherId($schedule, $year , $term, $teacherId) {
        $courseTeacherDao = new CourseTeacherDao();
        $list = $courseTeacherDao->getCoursesByTeacher($teacherId, true);
        $valList = [];
        foreach ($list as $item) {
            if ($term == $item->course->term && ($item->course->optional == Course::OBLIGATORY_COURSE ||$year == $item->course->courseElective->start_year)) {
                foreach ($item->course->courseArrangements as $arrangement) {
                    $valList[] = 'w_' . $arrangement->week . '_d_' . $arrangement->day_index . '_t_' . $arrangement->time_slot_id;
                }
            }
        }
        if (empty($valList)) {
            return false;
        }
        foreach ($schedule as $key => $group) {
            foreach ($group['weeks'] as $week) {
                foreach ($group['days'] as $day) {
                    foreach ($group['timeSlots'] as $timeSlot) {
                        $val = 'w_' . $week . '_d_' . $day . '_t_' . $timeSlot;
                        if (in_array($val, $valList)) {
                            return ['week' => $week, 'day' => $day, 'time' => $timeSlot];
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function deleteArrangementItem($id)
    {
        return ApplyCourseArrangement::where('id', $id)->delete();
    }

    /**
     * 根据学校获取分页
     * @param $schoolId
     * @return mixed
     */
    public function getPaginatedApplications($schoolId)
    {
        return TeacherApplyElectiveCourse::where('school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    public function getApplicationByTeacherById($applyid, $schoolId)
    {
        $item = TeacherApplyElectiveCourse::with('course')->where('id', '=', $applyid)->first();
        $nowDate = Carbon::now()->timestamp;
        //将周转换成日期
        $config = (new SchoolDao())->getSchoolById($schoolId)->configuration;
        $year = $item->start_year . '-' . ($item->term == SchoolConfiguration::LAST_TERM ? SchoolConfiguration::FIRST_TERM_START_MONTH : SchoolConfiguration::SECOND_TERM_START_MONTH) . '-01';
        $weeks = $config->getAllWeeksOfTerm($item->term, false, $year);
        $weekList = [];
        foreach ($weeks as $week) {
            $weekList[$week->getname()] = $week;
        }
        $tmp = [
            'applyid' => $item->id,
            'name' => $item->name,
            'teacher_name' => $item->teacher_name,
            'scores' => $item->course->scores,
            'max_num' => $item->course->courseElective->max_num > 0 ? $item->course->courseElective->max_num : $item->course->courseElective->open_num,
            'desc' => $item->desc,
            'apply_content' => $item->apply_content,
            'reply_content' => $item->reply_content,
            'created_at' => $item->created_at->toDateTimeString(),//?为什么自动转换会变成年-月-日
            'elective_status' => $item->course->courseElective->status,
            'elective_enrol_start_at' => Carbon::parse($item->course->courseElective->enrol_start_at),
            'elective_expired_at' => Carbon::parse($item->course->courseElective->expired_at),
            'open_num' => $item->course->courseElective->open_num
        ];
        foreach ($item->course->arrangements as $arrangement) {
            $tmp['arrangement'][] = [
                'week' => $arrangement->week,
                'week_day' => $weekList['第' . $arrangement->week . '周']->getstart(),
                'day_index' => $arrangement->day_index,
                'time' => $arrangement->timeslot->name,
                'building' => $arrangement->building_name,
                'classroom' => $arrangement->classroom_name,
            ];
        }
        $tmp['min_day'] = reset($tmp['arrangement'])['week_day'];
        $tmp['max_day'] = end($tmp['arrangement'])['week_day'];
        if ($tmp['elective_status'] == CourseElective::STATUS_CANCEL) {
            $tmp['status'] = 1;//已关闭
            $retList[] = $tmp;
        } else {
            if ($nowDate < $tmp['elective_enrol_start_at']->timestamp) {
                $tmp['status'] = 2;//待选课
            } elseif ($nowDate < $tmp['elective_expired_at']->timestamp) {
                $tmp['status'] = 3;//报名中
            } elseif ($nowDate < $tmp['min_day']->timestamp) {
                $tmp['status'] = 4;//待开课
            } elseif ($nowDate > $tmp['max_day']->timestamp) {
                $tmp['status'] = 5;//已结束
            } elseif ($nowDate > $tmp['min_day']->timestamp) {
                $tmp['status'] = 6;//进行中
            } else {
                $tmp['status'] = 7;//异常
            }
        }
        $userList = StudentEnrolledOptionalCourse::with('user')->where(['course_id' => $item->course_id, 'teacher_id' => $item->teacher_id])->get();
        $tmp['user_list'] = [];
        foreach ($userList as $user) {
            $tmp['user_list'][] = [
                'name' => $user->user->name,
                'avatar' => $user->user->profile->avatar,
                'major' => $user->user->gradeUser->major->name,
                'created_at' => $user->created_at
            ];
        }

        $tableName = 'student_enrolled_optional_courses_'.$item->course->courseElective->start_year.'_'.$item->course->term;
        $userList2 = DB::table($tableName)->where(['course_id' => $item->course_id, 'teacher_id' => $item->teacher_id])->get();
        foreach ($userList2 as $user2) {
            $user = User::where('id',$user2->user_id)->first();
            $tmp['user_list'][] = [
                'name' => $user->name,
                'avatar' => $user->profile->avatar,
                'major' => $user->gradeUser->major->name,
                'created_at' => $user2->created_at
            ];
        }

        return $tmp;
    }

    public function getApplication2ByTeacherById($applyid)
    {
        $item = TeacherApplyElectiveCourse::where('id', '=', $applyid)->first();
        $tmp = [
            'applyid' => $item->id,
            'name' => $item->name,
            'teacher_name' => $item->teacher_name,
            'scores' => 0,
            'max_num' => $item->max_num > 0 ? $item->max_num : $item->open_num,
            'open_num' => $item->open_num,
            'desc' => $item->desc,
            'apply_content' => $item->apply_content,
            'reply_content' => $item->reply_content,
            'created_at' => $item->created_at->toDateTimeString(),//?为什么自动转换会变成年-月-日
            'elective_status' => 0,
            'elective_enrol_start_at' => '',
            'elective_expired_at' => '',
            'status' => $item->status == TeacherApplyElectiveCourse::STATUS_WAITING_FOR_VERIFIED ? 8 : 9,
        ];
        foreach ($item->arrangements as $arrangement) {
            $tmp['arrangement'][] = [
                'week' => $arrangement->week,
                'week_day' => '',
                'day_index' => $arrangement->day_index,
                'time' => $arrangement->timeslot->name,
                'building' => $arrangement->building_name,
                'classroom' => $arrangement->classroom_name,
            ];
        }
        $tmp['min_day'] = '';
        $tmp['max_day'] = '';
        $tmp['user_list'] = [];
        return $tmp;
    }

    /**
     * 根据教师获取分页
     * @param $teacherId
     * @param $schoolId
     * @param $type
     * @param $page
     * @return array
     */
    public function getApplicationsByTeacher($teacherId, $schoolId, $type, $page)
    {
        //UI的分类与表结构太不一致,全部拿到内存处理
        $list = TeacherApplyElectiveCourse::with('course')
            ->where('teacher_id', '=', $teacherId)
            ->where('status', TeacherApplyElectiveCourse::STATUS_PUBLISHED)
            ->where('course_id', '>', 0)->orderBy('id', 'desc')->get();
        $retList = [];
        $nowDate = Carbon::now()->timestamp;
        foreach ($list as $item) {

            //将周转换成日期
            $config = (new SchoolDao())->getSchoolById($schoolId)->configuration;
            $year = $item->start_year . '-' . ($item->term == SchoolConfiguration::LAST_TERM ? SchoolConfiguration::FIRST_TERM_START_MONTH : SchoolConfiguration::SECOND_TERM_START_MONTH) . '-01';
            $weeks = $config->getAllWeeksOfTerm($item->term, false, $year);
            $weekList = [];
            foreach ($weeks as $week) {
                $weekList[$week->getname()] = $week;
            }

            $tmp = [
                'applyid' => $item->id,
                'name' => $item->name,
                'created_at' => $item->created_at->toDateTimeString(),//?为什么自动转换会变成年-月-日
                'elective_status' => $item->course->courseElective->status,
                'elective_enrol_start_at' => Carbon::parse($item->course->courseElective->enrol_start_at),
                'elective_expired_at' => Carbon::parse($item->course->courseElective->expired_at)
            ];
            foreach ($item->course->arrangements as $arrangement) {
                $tmp['arrangement'][] = [
                    'week' => $arrangement->week,
                    'week_day' => $weekList['第' . $arrangement->week . '周']->getstart(),
                    'day_index' => $arrangement->day_index,
                    'time' => $arrangement->timeslot->name
                ];
            }
            $tmp['min_day'] = reset($tmp['arrangement'])['week_day'];
            $tmp['max_day'] = end($tmp['arrangement'])['week_day'];
            if (empty($type)) {
                if ($tmp['elective_status'] == CourseElective::STATUS_CANCEL) {
                    $tmp['status'] = 1;//已关闭
                    $retList[] = $tmp;
                } else {
                    if ($nowDate < $tmp['elective_enrol_start_at']->timestamp) {
                        $tmp['status'] = 2;//待选课
                        $retList[] = $tmp;
                    } elseif ($nowDate < $tmp['elective_expired_at']->timestamp) {
                        $tmp['status'] = 3;//报名中
                        $retList[] = $tmp;
                    } elseif ($nowDate < $tmp['min_day']->timestamp) {
                        $tmp['status'] = 4;//待开课
                        $retList[] = $tmp;
                    } else {

                    }
                }
            } else {
                if ($tmp['elective_status'] != CourseElective::STATUS_CANCEL) {
                    if ($nowDate > $tmp['max_day']->timestamp) {
                        $tmp['status'] = 5;//已结束
                        $retList[] = $tmp;
                    } elseif ($nowDate > $tmp['min_day']->timestamp) {
                        $tmp['status'] = 6;//进行中
                        $retList[] = $tmp;
                    }
                }
            }
        }
        $return = [];
        $i = 0;
        $start = ($page - 1) * ConfigurationTool::DEFAULT_PAGE_SIZE;
        $end = $start + ConfigurationTool::DEFAULT_PAGE_SIZE;
        foreach ($retList as $item) {
            if ($i >= $start) {
                $return[] = $item;
            }
            if ($i >= $end) {
                break;
            }
            $i++;
        }
        return $return;
    }

    /**
     * 根据教师获取分页-未通过审核时 无course数据
     * @param $teacherId
     * @param $schoolId
     * @return array
     */
    public function getApplications2ByTeacher($teacherId, $schoolId)
    {
        $list = TeacherApplyElectiveCourse::where('teacher_id', '=', $teacherId)
            ->whereIn('status', [TeacherApplyElectiveCourse::STATUS_WAITING_FOR_VERIFIED, TeacherApplyElectiveCourse::STATUS_WAITING_FOR_REJECTED])
            ->orderBy('id', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
        $retList = [];
        foreach ($list as $item) {
            $tmp = [
                'applyid' => $item->id,
                'name' => $item->name,
                'created_at' => $item->created_at->toDateTimeString(),//?为什么自动转换会变成年-月-日
                'elective_status' => 0,
                'elective_enrol_start_at' => '',
                'elective_expired_at' => '',
                'min_day' => '',
                'max_day' => '',
                'status' => $item->status == TeacherApplyElectiveCourse::STATUS_WAITING_FOR_VERIFIED ? 8 : 9,
            ];
            foreach ($item->arrangements as $arrangement) {
                $tmp['arrangement'][] = [
                    'week' => $arrangement->week,
                    'week_day' => '',
                    'day_index' => $arrangement->day_index,
                    'time' => $arrangement->timeslot->name
                ];
            }
            $retList[] = $tmp;
        }
        return $retList;
    }

    /**
     * 获取已经审批过的申请
     * @param $id
     * @return TeacherApplyElectiveCourse|bool
     */
    public function getVerifiedApplyById($id)
    {
        $obj = $this->getApplyById($id);
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
                $bag = $this->saveApplyCourseArrangements($applyGroups, $apply->id);
                //保存关联专业
                $this->saveApplyMajor($data['course']['majors'], $apply->id, $data['course']['school_id']);

                if ($bag->isSuccess()) {
                    DB::commit();
                    $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                    $messageBag->setData($apply);
                } else {
                    DB::rollBack();
                    $messageBag->setMessage($bag->getMessage());
                }
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
        $data['course']['status'] = $data['course']['status'] ?? TeacherApplyElectiveCourse::STATUS_WAITING_FOR_VERIFIED;
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
                $this->saveApplyMajor($data['course']['majors'], $id, $data['course']['school_id']);
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
        return $this->operateApply($id, TeacherApplyElectiveCourse::STATUS_WAITING_FOR_REJECTED, $content);
    }

    /**
     * 解散一门选修课
     * @param $id
     */
    public function discolved($courseId)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        $course = Course::where('id', $courseId)->first();
        $tableName = 'student_enrolled_optional_courses_'.$course->courseElective->start_year.'_'.$course->term;
        DB::beginTransaction();
        try {
            //标记申请状态为驳回
            TeacherApplyElectiveCourse::where('course_id', $courseId)->update([
                'status' => TeacherApplyElectiveCourse::STATUS_WAITING_FOR_REJECTED,
                'reply_content' => '管理员取消'
            ]);
            //对应课程删除
            $dao = new CourseDao();
            $dao->deleteCourseByUuid($course->uuid);
            //标记选修课状态为取消
            CourseElective::where('course_id', $courseId)->update([
                'status' => CourseElective::STATUS_CANCEL
            ]);
            DB::commit();

            //通知教师
            $apply = TeacherApplyElectiveCourse::where('course_id', $courseId)->first();
            $teacher = User::find($apply->teacher_id);
            event(new ApproveElectiveTeacherEvent($teacher, $apply, 0));

            //通知所有报名的人
            $userList = [];
            $userIdArr = StudentEnrolledOptionalCourse::where('course_id', $course->id)->pluck('user_id')->toArray();
            if ($userIdArr) {
                $userList = array_merge($userList, $userIdArr);
            }

            $userIdArr2 = DB::table($tableName)->where('course_id', $course->id)->pluck('user_id')->toArray();
            if ($userIdArr2) {
                $userList = array_merge($userList, $userIdArr2);
            }
            foreach ($userList as $userId) {
                $user = User::find($userId);
                event(new ApproveElectiveCourseEvent($user, $course, 0));
            }
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);

        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    /**
     * 批准申请
     * @param $id
     * @param $content
     * @param array $newSchedule
     * @return IMessageBag
     */
    public function approvedApply($id, $content, $newSchedule)
    {
        foreach ($newSchedule as $item) {
            ApplyCourseArrangement::where('id', $item['id'])->update([
                'week' => $item['weeks'][0],
                'day_index' => $item['days'][0],
                'time_slot_id' => $item['timeSlots'][0],
                'building_id' => $item['building_id'],
                'building_name' => $item['building_name'],
                'classroom_id' => $item['classroom_id'],
                'classroom_name' => $item['classroom_name'],
            ]);
        }
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
     * @param int $status
     * @param $content
     * @return IMessageBag
     */
    public function operateApply($id, $status = TeacherApplyElectiveCourse::STATUS_VERIFIED, $content = '同意')
    {
        $msgBag = new MessageBag(JsonBuilder::CODE_ERROR, '系统错误');
        DB::beginTransaction();
        try {
            $apply = TeacherApplyElectiveCourse::where('id', $id)->first();

            if (TeacherApplyElectiveCourse::STATUS_VERIFIED === $status) {
                $apply->status = TeacherApplyElectiveCourse::STATUS_VERIFIED;
                $apply->reply_content .= $content;
            } elseif (in_array($status, [TeacherApplyElectiveCourse::STATUS_WAITING_FOR_REJECTED, TeacherApplyElectiveCourse::STATUS_PUBLISHED])) {
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
        if ($user->isSchoolAdminOrAbove()) {
            return TeacherApplyElectiveCourse::STATUS_VERIFIED;
        } else {
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
        foreach ($applyMajors->toArray() as $majorArr) {
            $majorArrs[] = $majorArr['major_id'];
        }
        //全部课时
        $groups = self::getApplyCourseArrangements($applyId);
        $data['group'] = $groups->toArray();

        //选修课表要求必须有building_id和classroom_id
        if (empty($data['group'])) {
            $messageBag->setMessage('请完善教室信息！');
            return $messageBag;
        }
        foreach ($data['group'] as $item) {
            if (empty($item['building_id']) || empty($item['classroom_id'])) {
                $messageBag->setMessage('请完善教室信息！');
                return $messageBag;
            }
        }
        $apply = self::getVerifiedApplyById($applyId);
        if (!$apply) {
            $messageBag->setMessage('没有审批过的申请不能发布！');
            return $messageBag;
        }
        //获取选修课的起止时间
        $electiveCourseStartAndEnd = $this->getElectiveCourseStartAndEndTime($apply->school_id, $apply->term);
        $data['school_id'] = $apply->school_id;
        $data['teachers'][0] = $apply->teacher_id;
        $data['majors'] = $majorArrs;
        $data['code'] = $apply->code;
        $data['name'] = $apply->name;
        $data['scores'] = $apply->scores;
        $data['optional'] = Course::ELECTIVE_COURSE;
        $data['year'] = $apply->year;
        $data['term'] = $apply->term;
        $data['desc'] = $apply->desc;
        $data['open_num'] = $apply->open_num;
        $data['max_num'] = $apply->max_num;
        $data['start_year'] = $apply->start_year;
        $data['enrol_start_at'] = $electiveCourseStartAndEnd[0];
        $data['expired_at'] = $electiveCourseStartAndEnd[1];


        DB::beginTransaction();
        try {
            //创建课程
            $courseDao = new CourseDao();
            $course = $courseDao->createCourse($data);
            if (!$course->isSuccess()) {
                DB::rollBack();
                $messageBag->setMessage($course->getData());
            }
            //标记申请表为发布状态
            $this->publishedApply($applyId);
            //记录一下申请和课表对应id
            TeacherApplyElectiveCourse::where('id', $applyId)->update(['course_id' => $course->getData()->id]);
            DB::commit();

            //通知教师
            $teacher = User::find($apply->teacher_id);
            event(new ApproveElectiveTeacherEvent($teacher, $apply, 1));
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setData($course->getData());
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
    public function getAllBySchool($schoolId, $monthsAgo = 3)
    {
        return TeacherApplyElectiveCourse::where('school_id', $schoolId)
            ->where('created_at', '>', Carbon::now()->subMonths($monthsAgo))
            ->get();
    }

    /**
     * @param $schedule
     * @param $applyId
     * @return IMessageBag
     */
    public function saveApplyCourseArrangements($schedule, $applyId)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_SUCCESS);
        if (!empty($schedule)) {
            $buildingDao = new BuildingDao();
            $roomDao = new RoomDao();

            try {
                foreach ($schedule as $key => $group) {
                    foreach ($group['weeks'] as $week) {
                        foreach ($group['days'] as $day) {
                            foreach ($group['timeSlots'] as $timeSlot) {
                                $buildingName = null;
                                $buildingId = null;
                                if (!empty($group['building_id'])) {
                                    $building = $buildingDao->getBuildingById($group['building_id']);
                                    if ($building) {
                                        $buildingName = $building->name;
                                        $buildingId = $group['building_id'];
                                    }
                                }

                                $roomName = null;
                                $roomId = null;
                                if (!empty($group['classroom_id'])) {
                                    $room = $roomDao->getRoomById($group['classroom_id']);
                                    if ($room) {
                                        $roomName = $room->name;
                                        $roomId = $group['classroom_id'];
                                    }
                                }

                                $d = [
                                    'apply_id' => $applyId,
                                    'week' => $week,
                                    'day_index' => $day,
                                    'time_slot_id' => $timeSlot,
                                    'building_id' => $buildingId,
                                    'building_name' => $buildingName,
                                    'classroom_id' => $roomId,
                                    'classroom_name' => $roomName,

                                ];
                                ApplyCourseArrangement::create($d);
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                $messageBag->setCode(JsonBuilder::CODE_ERROR);
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
        if (!empty($data)) {
            foreach ($data as $major) {
                $majorObj = self::getMajor($major);
                $f = [
                    'apply_id' => $applyId,
                    'school_id' => $schoolId,
                    'major_id' => $major,
                    'major_name' => $majorObj->name,
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
    public function getApplyCourseArrangements($applyId)
    {
        return ApplyCourseArrangement::where('apply_id', $applyId)->get();
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
        return $num1 + $num2;
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

    public function checkHasEnrolled($user, $courseId, $tableName)
    {
        $result = StudentEnrolledOptionalCourse::where('user_id', $user->id)->where('course_id', $courseId)->first();
        if (!$result) {
            $result = DB::table($tableName)->where('user_id', $user->id)->where('course_id', $courseId)->first();
        }
        return  $result;
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
        CREATE TABLE IF NOT EXISTS ' . $tableName . ' (
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
          KEY `idx_user_id` (`user_id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ';
        if (!Schema::hasTable($tableName)) {
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
        return CourseElective::where('course_id', $courseId)->first()->status == CourseElective::STATUS_ISFULL;
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
            'teacher_id' => $teacherId,
            'user_id' => $userId,
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
        if (self::getEnrolledTotalForCourses($courseId) >= $maxNum) {
            DB::beginTransaction();
            try {
                //先修改course_elective表的状态，让其他用户不能报名
                $updateNum = CourseElective::where('course_id', $courseId)
                    ->update(['status' => CourseElective::STATUS_ISFULL]);
                if ($updateNum == 1) {
                    $result = StudentEnrolledOptionalCourse::where('course_id', $courseId)
                        ->orderBy('id', 'ASC')->limit($maxNum)->get();
                    $i = 0;
                    foreach ($result as $rowObj) {

                        $d = [
                            'course_id' => $rowObj->course_id,
                            'teacher_id' => $rowObj->teacher_id,
                            'user_id' => $rowObj->user_id,
                            'school_id' => $rowObj->school_id,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
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

    public function getEnrolledResultTotalForCourse($courseId, $tableName)
    {
        return DB::table($tableName)->where('course_id', $courseId)->count();
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

    public function getElectiveCourseStartAndEndTime($schoolId, $term)
    {
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        if ($term == Course::FIRST_TERM)
        {
            $start = $school->configuration->apply_elective_course_from_1;
            $end = $school->configuration->apply_elective_course_to_1;
        } else {
            $start = $school->configuration->apply_elective_course_from_2;
            $end = $school->configuration->apply_elective_course_to_2;
        }
        $year = date("Y-");
        return [$year.date("m-d",strtotime($start)), $year.date("m-d",strtotime($end))];
    }

    public function gettoDissolvedElectiveList()
    {
        $nowTime = Carbon::now()->format('Y-m-d H:i:s');
        return CourseElective::where('status', CourseElective::STATUS_WAITING)->where('expired_at', '<', $nowTime)->get();
    }

    public function noticeStartElective()
    {
        $date = Carbon::now()->format('Y-m-d 00:00:00');
        $list = CourseElective::where('status', '<>', CourseElective::STATUS_CANCEL)->where('expired_at', $date)->get();
        foreach ($list as $elective) {
            $course = $elective->course;
            $tableName = 'student_enrolled_optional_courses_'.$elective->start_year.'_'.$course->term;
            //通知所有报名的人
            $userList = [];
            $userIdArr = StudentEnrolledOptionalCourse::where('course_id', $course->id)->pluck('user_id')->toArray();
            if ($userIdArr) {
                $userList = array_merge($userList, $userIdArr);
            }

            $userIdArr2 = DB::table($tableName)->where('course_id', $course->id)->pluck('user_id')->toArray();
            if ($userIdArr2) {
                $userList = array_merge($userList, $userIdArr2);
            }
            foreach ($userList as $userId) {
                $user = User::find($userId);
                event(new ApproveElectiveCourseEvent($user, $course, 1));
            }
        }
    }
}

