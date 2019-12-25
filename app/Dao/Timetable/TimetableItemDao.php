<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/10/19
 * Time: 2:58 PM
 */

namespace App\Dao\Timetable;
use App\Dao\Schools\SchoolDao;
use App\Models\Timetable\TimetableItem;
use App\User;
use App\Utils\Time\CalendarWeek;
use App\Utils\Time\GradeAndYearUtil;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class TimetableItemDao
{
    /**
     * @var array 当前关联的时间段记录
     */
    protected $timeSlots;
    public function __construct($timeSlots = [])
    {
        $this->timeSlots = $timeSlots;
    }

    /**
     * @param $teacherId
     * @param $year
     * @param $term
     * @return Collection
     */
    public function getItemsByTeacherForApp($teacherId, $year, $term){
        return TimetableItem::select('grade_id')->where('year',$year)
            ->where('term',$term)
            ->where('teacher_id',$teacherId)
            ->get();
    }

    /**
     * @param $id
     * @param $withRelations
     * @return TimetableItem
     */
    public function getItemById($id, $withRelations = false){
        if($withRelations){
            return TimetableItem::where('id',$id)
                ->with('building')
                ->with('room')
                ->with('timeSlot')
                ->first();
        }
        return TimetableItem::find($id);
    }

    /**
     * @param $item
     * @return TimetableItem|bool
     */
    public function cloneItem($item){
        $origin = $this->getItemById($item['id']);
        if($origin){
            $fillable = $origin->getFillable();
            $data = [];
            foreach ($fillable as $fieldName) {
                $data[$fieldName] = $origin->$fieldName;
            }
            $data['weekday_index'] = $item['weekday_index'];
            $data['time_slot_id'] = $item['time_slot_id'];
            return $this->createTimetableItem($data);
        }
        return false;
    }

    /**
     * @param $specialCase
     * @param TimetableItem $origin
     * @param User $doer
     * @return null|TimetableItem
     */
    public function createSpecialCase($specialCase, $origin, $doer){
        $fillable = $origin->getFillable();
        $data = [];
        foreach ($fillable as $fieldName) {
            $data[$fieldName] = $origin->$fieldName;
        }
        foreach ($specialCase as $name=>$fieldValue) {
            if($name === 'at_special_datetime' || $name === 'to_special_datetime'){
                $carbon = GradeAndYearUtil::ConvertJsTimeToCarbon($fieldValue);
                if($carbon){
                    $fieldValue = $carbon->format('Y-m-d');
                    $data[$name] = $fieldValue;
                }
            }
            else{
                $data[$name] = $fieldValue;
            }
        }
        $data['last_updated_by'] = $doer->id;
        return $this->createTimetableItem($data);
    }

    /**
     * 添加新的 Item
     * @param $data
     * @return TimetableItem
     */
    public function createTimetableItem($data){
        return TimetableItem::create($data);
    }

    /**
     * 检查给定的数据, 是否已经被别人项占用
     * @param $data
     * @return bool|TimetableItem
     */
    public function hasAnyOneTakenThePlace($data){
        // 情况 1: 如果课程表项目, 它的重复周期是每周有效, 那么插入前要检查, 只要该时段有任意类型的项目, 则都不可插入
        if(intval($data['repeat_unit']) === GradeAndYearUtil::TYPE_EVERY_WEEK){
            $where = [
                ['school_id','=',$data['school_id']],
                ['weekday_index','=',$data['weekday_index']],
                ['time_slot_id','=',$data['time_slot_id']],
                ['year','=',$data['year']],
                ['term','=',$data['term']],
                ['building_id','=',$data['building_id']],
                ['room_id','=',$data['room_id']],
            ];
        }
        elseif(intval($data['repeat_unit']) === GradeAndYearUtil::TYPE_EVERY_EVEN_WEEK){
            $where = [
                ['school_id','=',$data['school_id']],
                ['weekday_index','=',$data['weekday_index']],
                ['time_slot_id','=',$data['time_slot_id']],
                ['year','=',$data['year']],
                ['term','=',$data['term']],
                ['building_id','=',$data['building_id']],
                ['room_id','=',$data['room_id']],
                ['repeat_unit','<>',GradeAndYearUtil::TYPE_EVERY_ODD_WEEK], // 想插入双周, 那么相同时间地点, 不能有双周或者每周的
            ];
        }
        elseif(intval($data['repeat_unit']) === GradeAndYearUtil::TYPE_EVERY_ODD_WEEK){
            $where = [
                ['school_id','=',$data['school_id']],
                ['weekday_index','=',$data['weekday_index']],
                ['time_slot_id','=',$data['time_slot_id']],
                ['year','=',$data['year']],
                ['term','=',$data['term']],
                ['building_id','=',$data['building_id']],
                ['room_id','=',$data['room_id']],
                ['repeat_unit','<>',GradeAndYearUtil::TYPE_EVERY_EVEN_WEEK], // 想插入单周, 那么相同时间地点, 不能有单周或者每周的
            ];
        }
        else{
            return true; // 错误的数据, 直接 reject
        }

        $found = TimetableItem::where($where)->first();
        return $found??false;
    }

    /**
     * 删除
     * @param $id
     * @param User|null $doer
     * @return bool|null
     */
    public function deleteItem($id, $doer = null){
        $item = $this->getItemById($id);
        if($item){
            if($doer){
                // 记录下是谁删除的
                $item->last_updated_by = $doer->id;
                $item->save();
            }
        }
        return TimetableItem::where('id',$id)->delete();
    }

    /**
     * @param $id
     * @param User|null $doer
     * @return bool
     */
    public function publishItem($id, $doer=null){
        $item = $this->getItemById($id);
        if($item){
            if($doer){
                // 记录下是谁删除的
                $item->last_updated_by = $doer->id;
            }
            $item->published = true;
            return $item->save();
        }
        return false;
    }

    /**
     * 更新课程表项
     * @param $data
     * @return bool
     */
    public function updateTimetableItem($data){
        if(isset($data['id']) && $data['id']){
            $id = $data['id'];
            unset($data['id']);
            return TimetableItem::where('id',$id)->update($data);
        }
        return false;
    }

    /**
     * 根据给定的条件加载某个班的某一天的课程表项列表
     * @param $weekDayIndex
     * @param $year
     * @param $weekType
     * @param $term
     * @param $gradeId
     * @return array
     */
    public function getItemsByWeekDayIndex($weekDayIndex, $year, $term, $weekType, $gradeId){
        $where = $this->_getItemsByWeekDayIndexBy($weekDayIndex, $year, $term, $weekType, ['grade_id'=>$gradeId]);
        /**
         * @var TimetableItem[] $rows
         */
        $rows = TimetableItem::where($where)->orderBy('time_slot_id','asc')->get();

        $result = [];

        foreach ($this->timeSlots as $timeSlot) {
            $result[$timeSlot->id] = '';
        }

        foreach ($rows as $row) {
            // 要判断一下, 是否为调课的记录
            $result[$row->time_slot_id] = [
                'course' => $row->course->name,
                'teacher'=> $row->teacher->name,
                'teacher_id'=> $row->teacher_id,
                'building'=>$row->building->name,
                'room'=>$row->room->name,
                'room_id'=>$row->room_id,
                'id'=>$row->id,
                'published'=>$row->published,
                'repeat_unit'=>$row->repeat_unit,
                'optional'=>$row->course->optional,
                'weekday_index'=>$row->weekday_index,
                'time_slot_id'=>$row->time_slot_id,
                'specials'=>'',
            ];
        }
        return $result;
    }

    public function getItemsByWeekDayIndexForApp($weekDayIndex, $year, $term, $weekType, $gradeId){
        $result = $this->getItemsByWeekDayIndex($weekDayIndex, $year, $term, $weekType,$gradeId);
        return array_values($result);
    }

    /**
     * 根据给定的条件加载某个课程的课程表项列表
     * @param $weekDayIndex
     * @param $year
     * @param $weekType
     * @param $term
     * @param $courseId
     * @return array
     */
    public function getItemsByWeekDayIndexForCourseView($weekDayIndex, $year, $term, $weekType, $courseId){
        $where = $this->_getItemsByWeekDayIndexBy($weekDayIndex, $year, $term, $weekType, ['course_id' => $courseId]);
        /**
         * @var TimetableItem[] $rows
         */
        $rows = TimetableItem::where($where)->orderBy('time_slot_id','asc')->get();

        $result = [];

        foreach ($this->timeSlots as $timeSlot) {
            $result[$timeSlot->id] = '';
        }

        foreach ($rows as $row) {
            // 要判断一下, 是否为调课的记录
            $result[$row->time_slot_id] = [
                'grade_name' => $row->grade->name,
                'teacher'=> $row->teacher->name,
                'teacher_id'=> $row->teacher_id,
                'building'=>$row->building->name,
                'room'=>$row->room->name,
                'room_id'=>$row->room_id,
                'id'=>$row->id,
                'published'=>$row->published,
                'repeat_unit'=>$row->repeat_unit,
                'optional'=>$row->course->optional,
                'weekday_index'=>$row->weekday_index,
                'time_slot_id'=>$row->time_slot_id,
                'specials'=>'',
            ];
        }
        return $result;
    }

    /**
     * 根据给定的条件加载 某个授课老师的 排课
     * @param $weekDayIndex
     * @param $year
     * @param $weekType
     * @param $term
     * @param $teacherId
     * @return array
*/
    public function getItemsByWeekDayIndexForTeacherView($weekDayIndex, $year, $term, $weekType, $teacherId){
        $where = $this->_getItemsByWeekDayIndexBy($weekDayIndex, $year, $term, $weekType, ['teacher_id' => $teacherId]);
        /**
         * @var TimetableItem[] $rows
         */
        $rows = TimetableItem::where($where)->orderBy('time_slot_id','asc')->get();

        $result = [];

        foreach ($this->timeSlots as $timeSlot) {
            $result[$timeSlot->id] = '';
        }

        foreach ($rows as $row) {
            // 要判断一下, 是否为调课的记录
            $result[$row->time_slot_id] = [
                'grade_name' => $row->grade->name,
                'course' => $row->course->name,
                'teacher'=>'',
                'teacher_id'=> $row->teacher_id,
                'building'=>$row->building->name,
                'room'=>$row->room->name,
                'room_id'=>$row->room_id,
                'id'=>$row->id,
                'published'=>$row->published,
                'repeat_unit'=>$row->repeat_unit,
                'optional'=>$row->course->optional,
                'weekday_index'=>$row->weekday_index,
                'time_slot_id'=>$row->time_slot_id,
                'specials'=>'',
            ];
        }
        return $result;
    }

    /**
     * 根据给定的条件加载 某个教室的 排课
     * @param $weekDayIndex
     * @param $year
     * @param $weekType
     * @param $term
     * @param $roomId
     * @return array
     */
    public function getItemsByWeekDayIndexForRoomView($weekDayIndex, $year, $term, $weekType, $roomId){
        $where = $this->_getItemsByWeekDayIndexBy($weekDayIndex, $year, $term, $weekType, ['room_id' => $roomId]);
        /**
         * @var TimetableItem[] $rows
         */
        $rows = TimetableItem::where($where)->orderBy('time_slot_id','asc')->get();

        $result = [];

        foreach ($this->timeSlots as $timeSlot) {
            $result[$timeSlot->id] = '';
        }

        foreach ($rows as $row) {
            // 要判断一下, 是否为调课的记录
            $result[$row->time_slot_id] = [
                'grade_name' => $row->grade->name,
                'course' => $row->course->name,
                'teacher'=>$row->teacher->name,
                'teacher_id'=> $row->teacher_id,
                'building'=>$row->building->name,
                'room'=>'',
                'room_id'=>$row->room_id,
                'id'=>$row->id,
                'published'=>$row->published,
                'repeat_unit'=>$row->repeat_unit,
                'optional'=>$row->course->optional,
                'weekday_index'=>$row->weekday_index,
                'time_slot_id'=>$row->time_slot_id,
                'specials'=>'',
            ];
        }
        return $result;
    }

    /**
     * 根据给定的条件加载课程表项列表
     * @param $weekDayIndex
     * @param $year
     * @param $term
     * @param $weekType
     * @param $by : 查询的关键字段数组 field=>value 键值对
     * @return array
     */
    private function _getItemsByWeekDayIndexBy($weekDayIndex, $year, $term, $weekType, $by){
        $where = [
            ['year','=',$year],
            ['term','=',$term],
            ['weekday_index','=',$weekDayIndex],
            ['to_replace','=',0], // 不需要调课记录
        ];

        foreach ($by as $k=>$v) {
            $where[] = [$k,'=',$v];
        }

        if($weekType === GradeAndYearUtil::WEEK_ODD){
            // 单周课程表, 那么就加载 每周 + 单周
            $where[] = [
                'repeat_unit','<>',GradeAndYearUtil::TYPE_EVERY_EVEN_WEEK
            ];
        }
        else{
            // 双周课程表, 那么就加载 每周 + 双周
            $where[] = [
                'repeat_unit','<>',GradeAndYearUtil::TYPE_EVERY_ODD_WEEK
            ];
        }
        return $where;
    }

    /**
     * 获取指定条件下的调课统计数据
     * 返回: [
     *      '原始的固定课表项 ID' => [调课项的 id 数组]
     * ]
     * @param $year
     * @param $term
     * @param $gradeId
     * @param $today
     * @return array
     */
    public function getSpecialsAfterToday($year, $term, $gradeId, $today){
        return $this->_getSpecialsAfterTodayBy($year, $term, $today, ['grade_id'=>$gradeId]);
    }

    /**
     * 获取指定条件下的调课统计数据: 从课程的角度出发
     * 返回: [
     *      '原始的固定课表项 ID' => [调课项的 id 数组]
     * ]
     * @param $year
     * @param $term
     * @param $courseId
     * @param $today
     * @return array
     */
    public function getSpecialsAfterTodayForCourseView($year, $term, $courseId, $today){
        return $this->_getSpecialsAfterTodayBy($year, $term, $today, ['course_id'=>$courseId]);
    }

    /**
     * 获取指定条件下的调课统计数据: 从课程的授课教师角度出发
     * 返回: [
     *      '原始的固定课表项 ID' => [调课项的 id 数组]
     * ]
     * @param $year
     * @param $term
     * @param $teacherId
     * @param $today
     * @return array
     */
    public function getSpecialsAfterTodayForTeacherView($year, $term, $teacherId, $today){
        return $this->_getSpecialsAfterTodayBy($year, $term, $today, ['teacher_id'=>$teacherId]);
    }

    /**
     * 获取指定条件下的调课统计数据: 从教室角度出发
     * 返回: [
     *      '原始的固定课表项 ID' => [调课项的 id 数组]
     * ]
     * @param $year
     * @param $term
     * @param $roomId
     * @param $today
     * @return array
     */
    public function getSpecialsAfterTodayForRoomView($year, $term, $roomId, $today){
        return $this->_getSpecialsAfterTodayBy($year, $term, $today, ['room_id'=>$roomId]);
    }

    public function _getSpecialsAfterTodayBy($year, $term, $today, $by){
        $where = [
            ['year','=',$year],
            ['term','=',$term],
            ['to_replace','>',0], // 只加载调课记录
            ['at_special_datetime','>=',$today->format('Y-m-d').' 00:00:00'], // 今天或者今天以后的
        ];

        foreach ($by as $k=>$v) {
            $where[] = [$k,'=',$v];
        }

        /**
         * @var TimetableItem[] $rows
         */
        $specialRows = TimetableItem::select(['id','to_replace'])
            ->where($where)->orderBy('time_slot_id','asc')->get();

        $specialCases = [];

        foreach ($specialRows as $specialRow) {
            if(isset($specialCases[$specialRow->to_replace])){
                $specialCases[$specialRow->to_replace][] = $specialRow->id;
            }
            else{
                $specialCases[$specialRow->to_replace] = [$specialRow->id];
            }
        }
        return $specialCases;
    }

    /**
     * @param $year
     * @param $term
     * @param $weekdayIndex
     * @param $timeSlotId
     * @param $buildingId
     * @param $published: 标识是否只查找已经发布的
     * @return array
     */
    public function getBookedRoomsId($year, $term, $weekdayIndex, $timeSlotId, $buildingId, $published = null){
        if($published){
            return TimetableItem::select('room_id')->where('year',$year)
                ->where('term',$term)
                ->where('weekday_index',$weekdayIndex)
                ->where('time_slot_id',$timeSlotId)
                ->where('building_id',$buildingId)
                ->where('published',$published)
                ->get()->toArray();
        }
        else{
            return TimetableItem::select('room_id')->where('year',$year)
                ->where('term',$term)
                ->where('weekday_index',$weekdayIndex)
                ->where('time_slot_id',$timeSlotId)
                ->where('building_id',$buildingId)
                ->get()->toArray();
        }
    }

    /**
     * 根据传入的 id 的数组, 加载全部列表
     * @param $ids
     * @return Collection
     */
    public function getItemsByIdArray($ids){
        return TimetableItem::whereIn('id',$ids)->get();
    }

    /**
     * 根据给定的用户获取 当前时间的 课程表项
     * @param User $user
     * @return null
     */
    public function getCurrentItemByUser(User $user){
        $now = Carbon::now(GradeAndYearUtil::TIMEZONE_CN);

        $school = (new SchoolDao())->getSchoolById($user->getSchoolId());

        $currentTimeSlot = GradeAndYearUtil::GetTimeSlot($now, $school->id);
        if($currentTimeSlot && $school){
            $weekdayIndex = $now->weekday();
            $year = $now->year; // Todo: 根据用户获取当前的课程表项时, 年不是当前, 而是当前学年

            $term = $school->configuration->guessTerm($now->month);
            if ($user->isStudent()) {
                $where = [
                    ['school_id','=',$school->id],
                    ['year','=',$year],
                    ['term','=',$term],
                    ['time_slot_id','=',$currentTimeSlot->id],
                    ['grade_id','=',$user->gradeUser->grade_id],
                    ['weekday_index','=',$weekdayIndex],
                ];
            } elseif ($user->isTeacher()) {
                $where = [
                    ['school_id','=',$school->id],
                    ['year','=',$year],
                    ['term','=',$term],
                    ['time_slot_id','=',$currentTimeSlot->id],
                    ['teacher_id','=',$user->id],
                    ['weekday_index','=',$weekdayIndex],
                ];
            } else  {
                return  false;
            }
            return TimetableItem::where($where)->first();
        }
        return null;
    }



    /**
     * 查询学期课程的总结束包含调课
     * @param int $gradeId 班级ID
     * @param int $courseId 课程ID
     * @param int $year 学年
     * @param int $term 学期
     * @param CalendarWeek $weeks 学期周
     * @return float|int
     */
    public function getCourseCountByCourseId($gradeId, $courseId, $year, $term, $weeks) {
        $week = $weeks->count();
        $map = ['grade_id'=>$gradeId, 'course_id'=>$courseId, 'year'=>$year, 'term'=>$term];
        $list = TimetableItem::where($map)->get();
        $num = [];
        $minusNum = [];
        foreach ($list as $key => $val) {
            // 判断正常课程
            if(empty($val->at_special_datetime) && empty($val->to_special_datetime)) {
                $num[$key] = $this->getCourseCountByRepeatUnit($val->repeat_unit, $week);
                // 减去调课
                $where = ['grade_id'=>$gradeId, 'time_slot_id'=>$val->time_slot_id, 'year'=>$year, 'term'=>$term];
                $minusList = TimetableItem::where($where)->whereNotNull(['at_special_datetime', 'to_special_datetime'])->get();
                if(count($minusList) >0) {
                    foreach ($minusList as $k => $v) {
                        $minusCount[$k] = $this->getAdjustCourseCount($v->at_special_datetime, $v->to_special_datetime, $weeks, $v->weekday_index);
                    }
                    $minusNum[$key] = array_sum($minusCount);
                }
            } else{
                // 增加的调课
                $count = $this->getAdjustCourseCount($val->at_special_datetime, $val->to_special_datetime, $weeks, $val->weekday_index);
                $num[$key] = $count;
            }
        }

        return array_sum($num) - array_sum($minusNum);

    }


    /**
     * 根据单双周获取上课的次数
     * @param $repeatUnit
     * @param $week
     * @return float|int
     */
    public function getCourseCountByRepeatUnit($repeatUnit, $week) {
        switch ($repeatUnit) {
            case GradeAndYearUtil::TYPE_EVERY_WEEK :  // 每周都有课
                return $week; break;
            case GradeAndYearUtil::TYPE_EVERY_ODD_WEEK : // 表示每单周都有课
                return ceil($week/2); break;
            case GradeAndYearUtil::TYPE_EVERY_EVEN_WEEK: // 表示每双周都有课
                return floor($week/2); break;
            default:return 0;
        }
    }


    /**
     * 获取调节课程的次数
     * @param Carbon $atSpecialDatetime  调课的开始时间
     * @param Carbon $toSpecialDatetime  调课的结束时间
     * @param CalendarWeek $weeks 学期周
     * @param int $weekDayIndex  课程当周第几天
     * @return int
     */
    public function getAdjustCourseCount($atSpecialDatetime, $toSpecialDatetime, $weeks, $weekDayIndex) {
        foreach ($weeks as $key => $val) {
            if($val->includes($atSpecialDatetime)) {
                /**
                 *  @var CalendarWeek $val
                 */
                // 学期第多少周
                $startWeekIndex = $val->getScheduleWeekIndex();
                // 当前周的第几天
                $startWeekday = $atSpecialDatetime->weekday();
                // 判断这周开始时间的天大于该课的当周天
                if($startWeekday > $weekDayIndex) {
                    $startWeekIndex = $startWeekIndex + 1;
                }
            }
            if($val->includes($toSpecialDatetime)) {
                $endWeekIndex = $val->getScheduleWeekIndex();
                $endWeekDay = $toSpecialDatetime->weekday();
                // 判断这周结束时间的天小于该课的当周天
                if($endWeekDay < $weekDayIndex) {
                    $endWeekIndex = $endWeekIndex - 1;
                }
            }
        }

        return $endWeekIndex - $startWeekIndex + 1;
    }


}
