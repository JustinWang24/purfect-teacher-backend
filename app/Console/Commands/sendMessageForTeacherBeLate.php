<?php

namespace App\Console\Commands;

use App\Dao\OA\OaAttendanceTeacherDao;
use App\Events\User\TeacherBeLateEvent;
use App\Models\Timetable\TimetableItem;
use Illuminate\Console\Command;

class sendMessageForTeacherBeLate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendMessageForTeacherBeLate {schoolId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '当教师迟到时发送消息给教务处，好让临时代课教师前往班级';

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
        $schoolId = $this->argument('schoolId');
        $dao = new OaAttendanceTeacherDao();
        $timeTableItemList = $dao->getPushlist($schoolId);
        if (!empty($timeTableItemList)) {
            foreach ($timeTableItemList as $timeTableItem)
            $timeTable = TimetableItem::find($timeTableItem);
            event(new TeacherBeLateEvent($timeTable));
        }
    }
}
