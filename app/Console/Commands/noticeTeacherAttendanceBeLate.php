<?php

namespace App\Console\Commands;

use App\Dao\TeacherAttendance\AttendanceDao;
use App\Jobs\Notifier\InternalMessage;
use App\Models\Misc\SystemNotification;
use Illuminate\Console\Command;

class noticeTeacherAttendanceBeLate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'noticeTeacherAttendanceBeLate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '通知未打卡教师要迟到了';

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
        $dao = new AttendanceDao();
        $morningList = $dao->getBeLates('morning');
        if ($morningList) {
            foreach ($morningList as $user) {
                InternalMessage::dispatchNow(
                    $user->getSchoolId(),
                    SystemNotification::FROM_SYSTEM,
                    $user->id,
                    SystemNotification::TYPE_NONE,
                    SystemNotification::PRIORITY_LOW,
                    '',
                    '',
                    '你还有10分钟就要迟到了！',
                    SystemNotification::TEACHER_CATEGORY_OAATTENDANCE,
                    json_encode([
                        'type' => 'teacher-attendance',
                        'param1' => '',
                        'param2' => ''
                    ])
                );
            }
        }
        $afternoonList = $dao->getBeLates('afternoon');
        if ($afternoonList) {
            foreach ($afternoonList as $user) {
                InternalMessage::dispatchNow(
                    $user->getSchoolId(),
                    SystemNotification::FROM_SYSTEM,
                    $user->id,
                    SystemNotification::TYPE_NONE,
                    SystemNotification::PRIORITY_LOW,
                    '',
                    '',
                    '你还有10分钟就要迟到了！',
                    SystemNotification::TEACHER_CATEGORY_OAATTENDANCE,
                    json_encode([
                        'type' => 'teacher-attendance',
                        'param1' => '',
                        'param2' => ''
                    ])
                );
            }
        }
    }
}
