<?php


namespace App\Dao\AttendanceSchedules;


use App\Dao\BuildFillableData;
use App\Models\AttendanceSchedules\AttendancePerson;
use App\Models\AttendanceSchedules\AttendanceSchedule;
use App\Models\AttendanceSchedules\AttendanceSchedulePerson;
use App\Models\AttendanceSchedules\AttendanceTask;
use App\Models\AttendanceSchedules\AttendanceTimeSlot;
use App\Models\AttendanceSchedules\SpecialAttendance;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceSchedulesDao
{
    use BuildFillableData;
    public function __construct()
    {
    }

    // 以下的方法是对礼县的值周的特殊处理
    public function getSpecialAttendances($schoolId){
        return SpecialAttendance::where('school_id', $schoolId)
            ->orderBy('start_date', 'desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
    // 为前端加载值周数据
    public function getSpecialAttendancesForApp($schoolId, $startDate){
        return SpecialAttendance::where('school_id', $schoolId)
            ->where('start_date','>=',$startDate)
            ->with('grade')
            ->orderBy('start_date', 'asc')
            ->get();
    }

    public function saveSpecial($data){
        $sd = Carbon::createFromFormat('Y-m-d',$data['start_date']);
        $data['end_date'] = $sd->addDays(6)->format('Y-m-d');
        return SpecialAttendance::create($data);
    }
    public function updateSpecial($data){
        $sd = Carbon::createFromFormat('Y-m-d',$data['start_date']);
        $data['end_date'] = $sd->addDays(6)->format('Y-m-d');
        $special = SpecialAttendance::find($data['id']);
        foreach ($data as $key => $datum) {
            $special->$key = $datum;
        }
        return $special->save();
    }
    public function getSpecial($id){
        return SpecialAttendance::find($id);
    }
    public function deleteSpecial($id){
        return SpecialAttendance::where('id',$id)->delete();
    }
    // 对礼县的值周的特殊处理 结束

    /**
     * @param $data
     * @return MessageBag
     */
    public function createTask($data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            unset($data['id']);
        }
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new AttendanceTask(), $data);
            $task = AttendanceTask::create($fillableData);
            if ($task) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($task);
            } else {
                DB::rollBack();
                $messageBag->setMessage('保存值周信息失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;

    }

    /**
     * @param $data
     * @return MessageBag
     */
    public function updateTask($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new AttendanceTask(), $data);
            $task = AttendanceTask::where('id', $id)->update($fillableData);
            if ($task) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($task);
            } else {
                DB::rollBack();
                $messageBag->setMessage('更新值周信息失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    /**
     * 创建一组默认的时间槽
     * @param $taskId
     * @return bool
     */
    public function addDefaultTimeSlotsForTask($taskId)
    {
        //已经创建过时间槽的不能再创建
        if (count(AttendanceTimeSlot::where('task_id', $taskId)->get())>0)
        {
            return true;
        }
        $data = [
            ['task_id' => $taskId, 'title' => '上午', 'start_time' => '08:00:00', 'end_time' => '12:00:00'],
            ['task_id' => $taskId, 'title' => '中午', 'start_time' => '12:00:00', 'end_time' => '14:00:00'],
            ['task_id' => $taskId, 'title' => '下午', 'start_time' => '14:00:00', 'end_time' => '18:00:00'],
            ['task_id' => $taskId, 'title' => '晚上', 'start_time' => '18:00:00', 'end_time' => '22:00:00'],
        ];
        DB::beginTransaction();
        try {
            foreach ($data as $item) {
                $this->addTimeSlotsForTask($item);
            }
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }

    }

    /**
     * @param $taskId
     * @param $data
     * @return bool
     */
    public function addTimeSlotsForTask($data)
    {
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new AttendanceTimeSlot(), $data);
            AttendanceTimeSlot::create($fillableData);

            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $data
     * @return bool
     */
    public function updateTimeSlotsForTask($data)
    {
        $id = $data['id'];
        unset($data['id']);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new AttendanceTimeSlot(), $data);
            AttendanceTimeSlot::where('id', $id)->update($fillableData);
            DB::commit();
            return true;
        } catch (\Exception $exception) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $schoolId
     * @param $timeSlotId
     * @return mixed
     */
    public function deleteTimeSlots($schoolId, $timeSlotId)
    {
        return AttendanceTimeSlot::where('id', $timeSlotId)
            ->where('school_id', $schoolId)->delete();
    }

    /**
     * @param $timeSlotId
     * @return mixed
     */
    public function getTimeSlot($timeSlotId)
    {
        return AttendanceTimeSlot::find($timeSlotId);
    }

    /**
     * 添加值周计划
     * 为了减少操作，应该是一个批量操作
     * 提交时的数据是一个一周的模板，每周都如此循环
     * @param $data
     * @return MessageBag
     */
    public function addSchedules($data)
    {
        //拿到针对的task的id
        $taskId = $data['taskId'];
        $schoolId = $data['school_Id'];
        //拿到模板数据
        $scheduleArr = $data['schedule'];
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            foreach ($scheduleArr as $week => $tmpSlotArr) {
                foreach ($tmpSlotArr as $slotId => $personIds) {
                    foreach ($personIds as $user_id) {
                        $d = [
                            'school_id'     => $schoolId,
                            'task_id'       => $taskId,
                            'time_slot_id'  => $slotId,
                            'user_id'       => $user_id,
                            'week'          => $week,

                        ];
                        $scheduleObj = AttendanceSchedule::create($d);
                    }
                }
            }
            DB::commit();
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setData($scheduleObj);
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    /**
     * @param $taskId
     * @return mixed
     */
    public function getTask($taskId)
    {
        return AttendanceTask::find($taskId);
    }


    /**
     * 获取某个学校的全部值周任务，按时间段获取，例如：
     * 当current==0 && cycle==week时表示获取本周数据
     * 当current==1 && cycle==week时表示获取下周数据
     * cycle可以为week和month
     * @param $schoolId
     * @param string $cycle
     * @param int $current
     * @return mixed
     */
    public function getAllTaskForSchool($schoolId, $cycle='week', $current=0)
    {
        $timeArr = $this->getTimes($current, $cycle);
        $result = AttendanceTask::where(
                function ($query) use ($timeArr) {
                    $query->orwhereBetween('start_time',$timeArr)
                          ->orwhereBetween('end_time',$timeArr)
                          ->orWhere([['start_time', '<=', $timeArr[0]],['end_time', '>=', $timeArr[1]]]);
                })
            ->where('school_id', $schoolId)
            ->orderby('id', 'DESC')
            ->get();
/*        $result = AttendanceTask::where([['start_time', '<=', $timeArr[0]],['end_time', '>=', $timeArr[1]]])
            ->where('school_id', $schoolId)
            ->orderby('id', 'DESC')
            ->get();*/
        return $result;


    }

    /**
     * 获取某个值周任务在某段时间内的所有记录
     * @param $schoolId
     * @param $taskId
     * @param string $cycle
     * @param int $current
     * @return mixed
     */
    public function getSomeoneTaskScheduleForSchool($schoolId, $taskId)
    {
        $result = AttendanceSchedule::where('task_id', $taskId)
            ->where('school_id', $schoolId)
            ->get();
        return $result;
    }

    /**
     *
     * @param $current
     * @param string $cycle
     * @return array|bool
     */
    public function getTimes($current=0, $cycle='week')
    {
        if ($cycle == 'week') {
            if ($current == 0) {
                $startStr = 'this week';
                $endStr = 'next week';
            } elseif ($current == 1) {
                $startStr = 'next week';
                $endStr = '+1 week Monday';
            } elseif ($current < 0) {
                $endStr = $current .' week Monday';
                $startStr = --$current .' week Monday';
            } else {
                $num = $current - 1;
                $startStr = '+ ' . $num . ' week Monday';
                $endStr = '+ ' . $current . ' week Monday';
            }
        } elseif ($cycle == 'month') {
            if ($current == 0) {
                $startStr = 'first day of this month';
                $endStr = 'last day of this month';
            } elseif ($current < 0) {
                $startStr = 'first day of '.$current.' month';
                $endStr = 'last day of '.$current.' month';
            } else {
                $startStr = 'first day of +'.$current.' month';
                $endStr = 'last day of +'.++$current.' month';
            }
        } else {
            return false;
        }
        $startTime = date("Y-m-d", strtotime($startStr));
        $endTime = date("Y-m-d", strtotime($endStr));
        return [$startTime, $endTime];
    }

    /**
     * 删除一条schedule记录
     * @param $scheduleId
     * @param $schoolId
     * @return bool
     */
    public function delSchedule($scheduleId, $schoolId)
    {
        $num = AttendanceSchedule::where('school_id', $schoolId)
            ->where('id', $scheduleId)
            ->delete();
        return $num>0;
    }


    public function getTaskBySchoolId($taskId, $schoolId)
    {
        return AttendanceTask::where('id', $taskId)->where('school_id', $schoolId)->first();
    }


}
