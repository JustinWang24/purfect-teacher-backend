<?php

namespace App\Console\Commands;

use App\Dao\AttendanceSchedules\AttendancesDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Timetable\TimeSlotDao;
use App\Dao\Timetable\TimetableItemDao;
use App\Dao\Users\GradeUserDao;
use App\Jobs\Notifier\InternalMessage;
use App\Models\AttendanceSchedules\Attendance;
use App\Models\AttendanceSchedules\AttendancesDetail;
use App\Models\Misc\SystemNotification;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class noticeStudentAttendanceSignin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'noticeStudentAttendanceSignin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通知还没签到的学生快下课了';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $list = [];

        // 获取所有学校
        $schoolDao = new SchoolDao;
        $schools = $schoolDao->getAllSchool();
        $schoolIds = [];
        foreach ($schools as $key => $school) {
            $schoolIds[] = $school->id;
        }
        $timeSlotDao = new TimeSlotDao;

        $attendanceDao    = new AttendancesDao;
        $timetableItemDao = new TimetableItemDao;
        $gradeUserDao = new GradeUserDao;

        // 当前时间 + 5分钟 业务需求下课前五分钟.
        $time = Carbon::parse('+5 Minute')->format('H:i');

        foreach ($schoolIds as $schoolId) {
            // 获取学周
            $school = $schoolDao->getSchoolById($schoolId);
            $configuration = $school->configuration;
            $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);
            $month = Carbon::parse($now)->month;
            $term = $configuration->guessTerm($month);
            $weeks = $configuration->getScheduleWeek($now, null, $term);
            $week = $weeks->getScheduleWeekIndex();

            // 获取当前时间是第几节课
            $timeSlot =  $timeSlotDao->getTimeSlotByCurrentTime($schoolId);
//            if($timeSlot->to == $time) {
                $timetables = $timetableItemDao->getCourseListByCurrentTime($schoolId, $timeSlot->id);
                foreach ($timetables as $timetable) {
                  // 班级学生
                  // $gradeUsers = $gradeUserDao->getGradeUserByGradeId($timetable->grade_id);
                  // 签到主表数据
                  $attendanceData = $attendanceDao->isAttendanceByTimetableAndWeek($timetable, $week);
                  // 签到详情表数据
                  $attendanceInfo = $attendanceData->details->where('mold', AttendancesDetail::MOLD_TRUANT);
                  // 取出签到详情所有user_id
                  // $userIds = $attendanceInfo->pluck('student_id')->toArray();
                  $notSignUser = [];
                  foreach ($attendanceInfo as $key => $val) {
                        $notSignUser[] = $val->user;
                  }
                  $list[] = [
                            'time_table_id' => $timetable->id,
                            'course_name' => $timetable->course->name,
                            'not_sign_user' => $notSignUser,
                  ];
                }
//            }
        }
        Log::info('定时器执行了', $list);
        /*
         * [
         *   {
         *     "time_table_id":1,
         *     "course_name":"",
         *     "not_sign_user": [User,User]
         *   }
         * ]
         */
        if (!empty($list)) {
            foreach ($list as $item) {
                if (!empty($item['not_sign_user'])) {
                    foreach ($item['not_sign_user'] as $user) {
                        //发送系统消息
                        InternalMessage::dispatchNow(
                            $user->getSchoolId(),//用户学校id
                            SystemNotification::FROM_SYSTEM,//消息来源默认系统消息
                            $user->id,//接收对象
                            SystemNotification::TYPE_NONE,//消息类型 已废弃
                            SystemNotification::PRIORITY_LOW,//消息级别 已废弃
                            '课程名称：' . $item['course_name'],//消息内容
                            '',//h5下一步链接 已废弃
                            '当前课程未签到！',//消息标题
                            SystemNotification::STUDENT_CATEGORY_SIGNIN,//消息类型 学生签到
                            json_encode([
                                        'type' => 'study-timetable-details',//客户端跳转关键字 课程表详情
                                        'param1' => $item['time_table_id'],//课表id
                                        'param2' => ''
                            ])
                        );
                    }
                }
            }
        }
    }
}
