<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/10/19
 * Time: 10:17 AM
 */

namespace App\Dao\Timetable;

use Carbon\Carbon;
use App\Models\School;
use App\Models\Schools\Room;
use App\Dao\Schools\SchoolDao;
use Illuminate\Support\Collection;
use App\Models\Timetable\TimeSlot;
use App\Utils\Time\GradeAndYearUtil;
use App\Models\Timetable\TimetableItem;
use App\Models\Schools\SchoolConfiguration;

class TimeSlotDao
{
    /**
     * @var School|null
     */
    private $currentSchool;
    public function __construct($school = null)
    {
        $this->currentSchool = $school;
    }

    public function getById($id){
        return TimeSlot::find($id);
    }

    public function update($id, $data){
        return TimeSlot::where('id',$id)->update($data);
    }

    /**
     * 获取系统设置的默认时间段
     * @param int $seasonType
     * @param bool $asJsonObject
     * @return array
     */
    public function getDefaultTimeFrame($seasonType = TimeSlot::SEASONS_SUMMER_AND_AUTUMN, $asJsonObject = false){
        $txt = file_get_contents(__DIR__.($seasonType === TimeSlot::SEASONS_SUMMER_AND_AUTUMN ? '/default_time_frames.json' : '/default_time_frames_summer.json'));
        return $asJsonObject ? $txt : json_decode($txt, true);
    }

    /**
     * 创建时间段
     * @param $data
     * @return TimeSlot
     */
    public function createTimeSlot($data){
        return TimeSlot::create($data);
    }

    /**
     * 获取所有用于学习的时间段: 上课 + 自习 + 自由活动
     * @param $schoolId
     * @param boolean $simple
     * @param boolean $noTime 不要时间
     * @return array|Collection
     */
    public function getAllStudyTimeSlots($schoolId, $simple = false, $noTime = false){
        $config = SchoolConfiguration::where('school_id',$schoolId)->first();
        $seasonType = GradeAndYearUtil::GetCurrentSeason($config);
        $slots = TimeSlot::where('school_id',$schoolId)
            ->where('season',$seasonType)
            ->whereIn('type',[TimeSlot::TYPE_STUDYING, TimeSlot::TYPE_PRACTICE, TimeSlot::TYPE_FREE_TIME])
            ->orderBy('from','asc')
            ->get();

        if(!$simple){
            $data = [];
            foreach ($slots as $slot) {
                $slot->current = $this->isCurrent($slot);
                $data[] = $slot;
            }
            return $data;
        }
        $result = [];

        foreach ($slots as $slot) {
            $name = $slot->name;
            if(!$noTime){
                $name .= ' ('.substr($slot->from,0,5).' - '.substr($slot->to,0,5).')';
            }
            $result[] = [
                'id'=>$slot->id,
                'name'=>$name,
                'from'=>$slot->from,
                'to'=>$slot->to,
                'type'=>$slot->type,
                'current'=>$this->isCurrent($slot),
            ];
        }

        return $result;
    }

    /**
     * 根据当前的时间点, 判断是否给定的 time slot 是当前
     * @param $timeSlot
     * @return bool
     */
    protected function isCurrent($timeSlot){
        $time = now(GradeAndYearUtil::TIMEZONE_CN)->format('H:i:s');
        return $timeSlot->from <= $time && $time < $timeSlot->to;
    }

    /**
     * 为云班牌提供当前教室的课程列表的方法.
     *
     * 提供当前上课的教室, 返回 Timetable Item 集合
     *
     * @param Room $room: 教室对象
     * @param Carbon|null $date: 日期, 默认为今天
     *
     * @return TimetableItem[]
     */
    public function getTimeSlotByRoom(Room $room, $date = null){
        if(!$date){
            $date = Carbon::now();
        }
        /**
         * @var School $school
         */
        $school = $room->school;
        $schoolConfiguration = $school->configuration;

        // 根据当前时间, 获取所在的学期, 年, 单双周, 第几节课
        $startDate = $schoolConfiguration->getTermStartDate();
        $year = $startDate->year;
        $term = $schoolConfiguration->guessTerm($date->month);

        $timeSlots = $this->getAllStudyTimeSlots($room->school_id);

        $currentTimeSlot = null;
        foreach ($timeSlots as $timeSlot) {
            /**
             * @var TimeSlot $timeSlot
             */
            if($timeSlot->current){
                $currentTimeSlot = $timeSlot;
            }
        }

        $timeSlots = TimetableItem::where('room_id',$room->id)
            ->where('year', $year)
            ->where('term', $term)
            ->where('weekday_index',$date->weekday())
            ->where('time_slot_id','>=',$currentTimeSlot->id)
            ->with('timeslot')
            ->orderBy('time_slot_id','asc')
            ->get();
        return $timeSlots;
    }

    /**
     * 根据房间号获取当前正在上课的的 Timetable Item
     * @param Room $room
     * @param null $date
     * @return TimetableItem|null
     */
    public function getItemByRoomForNow(Room $room, $date = null){
        if(!$date){
            $date = Carbon::now();
        }
        /**
         * @var School $school
         */
        $school = $room->school;
        $schoolConfiguration = $school->configuration;

        // 根据当前时间, 获取所在的学期, 年, 单双周, 第几节课
        $startDate = $schoolConfiguration->getTermStartDate();
        $year = $startDate->year;
        $term = $schoolConfiguration->guessTerm($date->month);

        $timeSlots = $this->getAllStudyTimeSlots($room->school_id);

        $currentTimeSlot = null;
        foreach ($timeSlots as $timeSlot) {
            /**
             * @var TimeSlot $timeSlot
             */
            if($timeSlot->current){
                $currentTimeSlot = $timeSlot;
            }
        }

        if (empty($currentTimeSlot->id)) {
            return null;
        }
        return TimetableItem::where('room_id',$room->id)
            ->where('year', $year)
            ->where('term', $term)
            ->where('weekday_index',$date->weekday())
            ->where('time_slot_id', $currentTimeSlot->id)
            ->with('timeslot')
            ->first();
    }


    /**
     * 获取当前时间的第几节课
     * @param $schoolId
     * @param $term
     * @param null $time
     * @return mixed
     */
    public function getTimeSlotByCurrentTime($schoolId, $term = null, $time = null) {

        if(is_null($term)) {
            $month = Carbon::now()->month;
            $schoolDao = new SchoolDao();
            $school = $schoolDao->getSchoolById($schoolId);
            $configuration = $school->configuration;
            $term = $configuration->guessTerm($month);
        }

        if(is_null($time)) {
            $time = Carbon::now()->toTimeString();
        }

        $map = [
            ['school_id', '=', $schoolId],
            ['from', '<', $time],
            ['to', '>', $time],
            ['season', '=', $term]
        ];
        return TimeSlot::where($map)->first();
    }
}
