<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/10/19
 * Time: 2:58 PM
 */

namespace App\Dao\Timetable;
use App\Models\Timetable\TimetableItem;
use App\User;
use App\Utils\Time\GradeAndYearUtil;
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
     * @param $id
     * @return TimetableItem
     */
    public function getItemById($id){
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
        $where = [
            ['year','=',$year],
            ['term','=',$term],
            ['grade_id','=',$gradeId],
            ['weekday_index','=',$weekDayIndex],
            ['to_replace','=',0], // 不需要调课记录
        ];

        if($weekType === GradeAndYearUtil::WEEK_ODD){
            // 单周课程表, 那么就加载 每周 + 单周
            $where[] = [
                'repeat_unit','<>',GradeAndYearUtil::TYPE_EVERY_EVEN_WEEK
            ];
        }else{
            // 双周课程表, 那么就加载 每周 + 双周
            $where[] = [
                'repeat_unit','<>',GradeAndYearUtil::TYPE_EVERY_ODD_WEEK
            ];
        }
        /**
         * @var TimetableItem[] $rows
         */
        $rows = TimetableItem::where($where)->orderBy('time_slot_id','asc')->get();

        $result = [];

        foreach ($this->timeSlots as $timeSlot) {
            $result[$timeSlot->id] = '';
        }

//        $specialCases = [];
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
        $where = [
            ['year','=',$year],
            ['term','=',$term],
            ['grade_id','=',$gradeId],
            ['to_replace','>',0], // 只加载调课记录
            ['at_special_datetime','>=',$today->format('Y-m-d').' 00:00:00'], // 今天或者今天以后的
        ];
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
}