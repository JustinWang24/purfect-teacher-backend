<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 23/10/19
 * Time: 10:17 AM
 */

namespace App\Dao\Timetable;
use App\Models\School;
use App\Models\Timetable\TimeSlot;

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

    /**
     * 获取系统设置的默认时间段
     * @param bool $asJsonObject
     * @return array
     */
    public function getDefaultTimeFrame($asJsonObject = false){
        $txt = file_get_contents(__DIR__.'/default_time_frames.json');
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
}