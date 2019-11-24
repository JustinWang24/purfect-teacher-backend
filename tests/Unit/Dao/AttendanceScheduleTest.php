<?php


namespace Tests\Unit\Dao;


use App\Models\AttendanceSchedules\AttendanceTask;
use App\Dao\AttendanceSchedules\AttendanceSchedulesDao;
use App\Dao\Users\UserDao;
use Illuminate\Support\Facades\DB;
use Tests\Feature\BasicPageTestCase;

class AttendanceScheduleTest extends BasicPageTestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testCanCreateTask()
    {
        $this->withoutExceptionHandling();
        $dao = new UserDao();
        $user = $dao->getUserByMobile('13911373227');
        $data = self::__createTaskData();
        $task = new AttendanceSchedulesDao();
        $taskObj = $task->createTask($data);
        $this->assertEquals(1000, $taskObj->getCode());
        $taskId =  $taskObj->getData()->id;
        $bool = $task->addDefaultTimeSlotsForTask($taskId);
        $this->assertEquals(true, $bool);
        $slotCollection = DB::table('attendance_time_slots')->where('task_id','=',$taskId)->get();
        $this->assertEquals(4, count($slotCollection));
        $scheles = [];
        $scheles['taskId'] = $taskId;
        $scheles['school_Id'] = 1;

        foreach ($slotCollection as $slot)
        {
            $scheles['schedule'][0][$slot->id][] = $user->id;
            $scheles['schedule'][1][$slot->id][] = $user->id;
            $scheles['schedule'][2][$slot->id][] = $user->id;
            $scheles['schedule'][3][$slot->id][] = $user->id;
            $scheles['schedule'][4][$slot->id][] = $user->id;
            $scheles['schedule'][5][$slot->id][] = $user->id;
            $scheles['schedule'][6][$slot->id][] = $user->id;
        }
        $result = $task->addSchedules($scheles);
        $this->assertEquals(1000, $result->getCode());

    }


    public function __createTaskData()
    {
        return ['school_id'=>1, 'type'=>1, 'title'=>'心理咨询', 'content'=>'接受学生的心理问题咨询',
            'start_time'=>'2019-11-20', 'end_time'=>'2019-12-30'];
    }
}
