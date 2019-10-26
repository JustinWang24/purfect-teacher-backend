<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 25/10/19
 * Time: 2:11 PM
 */

namespace App\BusinessLogic\AvailableRooms\TimetableApi;

use App\BusinessLogic\AvailableRooms\Contracts\IQueryAvailableRooms;
use App\Dao\Schools\RoomDao;
use App\Dao\Timetable\TimetableItemDao;
use App\User;
use Illuminate\Http\Request;

class QueryAvailableClassrooms implements IQueryAvailableRooms
{
    protected $buildingId;
    protected $year;
    protected $term;
    protected $weekdayIndex;
    protected $timeSlotId;

    protected $roomDao;
    protected $timetableItemDao;

    public function __construct(Request $request)
    {
        $this->buildingId = $request->get('building');
        $this->year = $request->get('year');
        $this->term = $request->get('term');
        $this->weekdayIndex = $request->get('weekday_index');
        $this->timeSlotId = $request->get('timeSlot');
        $this->roomDao = new RoomDao(new User());
        $this->timetableItemDao = new TimetableItemDao();
    }

    /**
     * 获取所有空余的房间
     * @return array|\Illuminate\Support\Collection
     */
    public function getAvailableRooms()
    {
        $rooms = $this->roomDao->getRoomsByBuilding($this->buildingId); // 获取所有的房间

        // 获取所有被占用的房间的 id, 是确认被占用
        $bookedRoomsId = $this->timetableItemDao->getBookedRoomsId(
            $this->year, $this->term, $this->weekdayIndex, $this->timeSlotId, $this->buildingId, true
        );

        if(!empty($bookedRoomsId)){
            $availableRooms = [];
            foreach ($rooms as $room) {
                if(!in_array($room->id, $bookedRoomsId)){
                    $availableRooms[] = $room;
                }
            }
        }else{
            $availableRooms = $rooms;
        }
        return $availableRooms;
    }
}