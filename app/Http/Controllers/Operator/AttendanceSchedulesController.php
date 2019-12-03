<?php
namespace App\Http\Controllers\Operator;
use App\Dao\AttendanceSchedules\AttendanceSchedulesDao;
use App\Dao\Users\GradeUserDao;
use App\Http\Controllers\Controller;
use App\Models\Acl\Role;
use App\Models\AttendanceSchedules\AttendanceSchedule;
use App\Utils\FlashMessageBuilder;
use Illuminate\Http\Request;

class AttendanceSchedulesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $schoolId = $request->session()->get('school.id');
        $dao = new AttendanceSchedulesDao();
        $taskList = $dao->getAllTaskForSchool($schoolId, 'month');
        $this->dataForView['tasks'] = $taskList;
        return view('school_manager.attendance.list', $this->dataForView);
    }

    public function add(Request $request){
        $task = new AttendanceSchedule();
        $this->dataForView['task'] = $task;
        return view('school_manager.attendance.add', $this->dataForView);
    }
    public function edit(Request $request, $id){
        $dao = new AttendanceSchedulesDao();
        $schoolId = $request->session()->get('school.id');
        $task = $dao->getTaskBySchoolId($id, $schoolId);
        $taskSlot = $task->timeSlots()->get();
        $taskSlotNum = count($taskSlot);

        $this->dataForView['task'] = $task;
        $this->dataForView['taskSlotNum'] = $taskSlotNum;
        return view('school_manager.attendance.edit', $this->dataForView);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $taskData = $request->get('task');
        $taskData['school_id'] = $request->session()->get('school.id');
        $dao = new AttendanceSchedulesDao();

        if(isset($taskData['id'])){
            $result = $dao->updateTask($taskData);
        }
        else{
            $result = $dao->createTask($taskData);
        }
        //创建默认时间槽
        if ($result->getCode()==1000)
        {
            $dao->addDefaultTimeSlotsForTask($result->getData()->id);
        }

        if($result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$taskData['title'].'值周任务保存成功');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'无法保存值周任务'.$taskData['title']);
        }
        return redirect()->route('school_manager.attendance.list');
    }

    public function editTimeSlots(Request $request, $taskId)
    {
        $dao = new AttendanceSchedulesDao();
        $schoolId = $request->session()->get('school.id');
        $taskObj = $dao->getTaskBySchoolId($taskId, $schoolId);
        $timeSlots = $taskObj->timeSlots()->get();
        $this->dataForView['timeSlots'] = $timeSlots;
        $this->dataForView['task'] = $taskObj;
        return view('school_manager.attendance.time_slots_edit', $this->dataForView);

    }

    /**
     * 编辑任务对应的时间槽
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateTimeSlots(Request $request)
    {
        $timeSlot = $request->get('timeslots');
        $taskId = $request->get('task');
        $dao = new AttendanceSchedulesDao();
        $schoolId = $request->session()->get('school.id');

        $taskObj = $dao->getTaskBySchoolId($taskId['id'], $schoolId);
        $timeSlotObj = $dao->getTimeSlot($timeSlot['id']);
        //确认此数据是当前用户所拥有
        if ($timeSlotObj->task_id == $taskId['id'])
        {
            $result = $dao->updateTimeSlotsForTask($timeSlot);
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'保存成功');
        }
        return redirect()->back();
    }

    /**
     * @param Request $request
     * @param $taskId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function searchPerson(Request $request, $taskId)
    {
        $search = $request->get('search');
        if ($search['type']==1)
        {
            $type = [9,10];
        } else {
            $type = [6,7,8];
        }
        $schoolId = $request->session()->get('school.id');
        if (!empty($search)) {
            $gradeDao = new GradeUserDao();
            $result = $gradeDao->getUsersWithNameLike($search['keyword'], $schoolId, $type);
            $userProfile = [];
            foreach ($result as $gradeUser) {
                $userProfile[$gradeUser->user_id]['slug'] = Role::AllNames()[Role::GetRoleSlugByUserType($gradeUser->user_type)];
                $userProfile[$gradeUser->user_id]['department'] = $gradeUser->department()->first()->name;
                $userProfile[$gradeUser->user_id]['major'] = $gradeUser->major()->first()->name;
        }
        }
        $dao = new AttendanceSchedulesDao();
        $taskObj = $dao->getTaskBySchoolId($taskId, $schoolId);
        $timeSlotsCollection = $taskObj->timeSlots()->get();


        $this->dataForView['search']        = $search;
        $this->dataForView['taskId']        = $taskId;
        $this->dataForView['result']        = $result??[];
        $this->dataForView['userProfile']   = $userProfile??[];
        $this->dataForView['timeSlots']     = $timeSlotsCollection??[];
        return view('school_manager.attendance.search', $this->dataForView);
    }

    /**
     * @param Request $request
     * @param $taskId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function addPerson(Request $request, $taskId)
    {
        $userId = $request->personId;
        $week = $request->week;
        $slotId = $request->slotId;
        $schoolId = $request->session()->get('school.id');
        $gradeDao = new GradeUserDao();
        $dao = new AttendanceSchedulesDao();
        $gradeObj = $gradeDao->isUserInSchool($userId, $schoolId);
        $taskObj = $dao->getTaskBySchoolId($taskId, $schoolId);
        if ($gradeObj && $taskObj)
        {
            $data = [
                'taskId' => $taskId,
                'school_Id' => $schoolId,
                'schedule'  => [
                    $week => [
                        $slotId=>[$userId]
                    ]
                ]
            ];
            $result = $dao->addSchedules($data);
            $result && FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'保存成功');
        }

        return redirect()->back();

    }

    /**
     * @param Request $request
     * @param $taskId
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function display(Request $request, $taskId)
    {
        $dao = new AttendanceSchedulesDao();
        $schoolId   = $request->session()->get('school.id');
        $taskObj    = $dao->getTaskBySchoolId($taskId, $schoolId);
        $slots      = $taskObj->timeSlots()->get();
        $schedules  = $dao->getSomeoneTaskScheduleForSchool($schoolId, $taskId);
        //拼有序数组
        $data = [];
        foreach ($schedules as $schedule)
        {
            $tmpUsers = $schedule->user()->get();
            foreach ($tmpUsers as $tmpUser)
            {
                $tmp['userName'] = $tmpUser->name;
                $tmp['department'] = $tmpUser->department()->first()->name;
                $tmp['major'] = $tmpUser->major()->first()->name;
            }
            $week = $schedule->week==0?7:$schedule->week;
            $data[$week][$schedule->time_slot_id][] = $tmp;
        }

        $time       = $dao->getTimes();
        $this->dataForView['task'] = $taskObj;
        $this->dataForView['slots'] = $slots;
        $this->dataForView['time'] = $time;
        $this->dataForView['data'] = $data;
        return view('school_manager.attendance.display', $this->dataForView);
    }
}
