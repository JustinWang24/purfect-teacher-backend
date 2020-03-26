<?php

namespace App\Console\Commands;

use App\Dao\TeacherAttendance\AttendanceDao;
use App\Jobs\Notifier\InternalMessage;
use App\Models\Misc\SystemNotification;
use Illuminate\Console\Command;

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
        //@TODO 获取5分后下课的课程以及还没签到的用户
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
