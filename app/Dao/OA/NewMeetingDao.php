<?php
/**
 * Created by PhpStorm.
 * User: liuyang
 * Date: 2020/2/10
 * Time: 下午1:05
 */

namespace App\Dao\OA;


use App\Models\OA\NewMeeting;

class NewMeetingDao
{

    /**
     * 根据时间获取会议
     * @param $schoolId
     * @param $date
     * @return mixed
     */
    public function getMeetingByDate($schoolId, $date){
        return NewMeeting::where('school_id', $schoolId)
            ->where('type', NewMeeting::TYPE_MEETING_ROOM)
            ->whereDate('meet_start', $date)
            ->get();
    }



}