<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 12:55 PM
 */

namespace App\Dao\Schools;
use App\User;
use App\Models\Schools\Room;
use Illuminate\Support\Collection;
use App\Models\Timetable\TimetableItem;

class RoomDao
{
    private $currentUser;
    public function __construct(User $user)
    {
        $this->currentUser = $user;
    }

    /**
     * @param $buildingId
     * @return Collection
     */
    public function getRoomsByBuilding($buildingId){
        return Room::where('building_id',$buildingId)->get();
    }

    /**
     * @param $year
     * @param $term
     * @param $weekdayIndex
     * @param $timeSlotId
     * @param $buildingId
     * @return array
     */
    public function getBookedRoomsId($year, $term, $weekdayIndex, $timeSlotId, $buildingId){
        return TimetableItem::select('room_id')->where('year',$year)
            ->where('term',$term)
            ->where('weekday_index',$weekdayIndex)
            ->where('time_slot_id',$timeSlotId)
            ->where('building_id',$buildingId)
            ->get()->toArray();
    }

    /**
     * @param $id
     * @return Room
     */
    public function getRoomById($id){
        return Room::find($id);
    }

    /**
     * @param $data
     * @return Room
     */
    public function createRoom($data){
        return Room::create($data);
    }

    /**
     * 删除房间数据
     * @param $roomId
     * @return mixed
     */
    public function deleteRoom($roomId){
        return Room::where('id',$roomId)->delete();
    }

    /**
     * 更新 Room 数据
     * @param $data
     * @param null $where
     * @param null $whereValue
     * @return mixed
     */
    public function updateRoom($data, $where = null, $whereValue = null){
        $id = $data['id'];
        unset($data['id']);
        if($where && $whereValue){
            return Room::where($where, $whereValue)->update($data);
        }
        return Room::where('id', $id)->update($data);
    }

}