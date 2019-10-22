<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Schools\RoomDao;
use App\Http\Requests\School\RoomRequest;
use App\Http\Controllers\Controller;
use App\Dao\Schools\BuildingDao;
use App\Models\Schools\Room;
use App\Utils\FlashMessageBuilder;

class RoomsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 加载添加建筑物的表单
     * @param RoomRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(RoomRequest $request){
        $dao = new BuildingDao($request->user());
        $this->dataForView['building'] = $dao->getBuildingById($request->uuid());
        $this->dataForView['room'] = new Room();
        return view('school_manager.room.add', $this->dataForView);
    }

    /**
     * 加载添加校区的表单
     * @param RoomRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(RoomRequest $request){
        $dao = new RoomDao($request->user());
        $room = $dao->getRoomById($request->uuid());
        $this->dataForView['room'] = $room;
        $this->dataForView['building'] = $room->building;
        return view('school_manager.room.edit', $this->dataForView);
    }

    /**
     * @param RoomRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(RoomRequest $request){
        $dao = new RoomDao($request->user());
        $room = $dao->getRoomById($request->uuid());
        $result = $dao->deleteRoom($request->uuid());

        if($room && $result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$room->name.'房间已经被成功删除');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'无法删除房间');
        }
        return redirect()->route('school_manager.building.rooms',['uuid'=>$room->building_id]);
    }

    /**
     * 保存校区的方法
     * @param RoomRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(RoomRequest $request){
        $roomData = $request->get('room');
        $roomData['school_id'] = $request->session()->get('school.id');
        $roomDao = new RoomDao($request->user());

        if(isset($roomData['id'])){
            $result = $roomDao->updateRoom($roomData);
        }
        else{
            // 新建房间
            $result = $roomDao->createRoom($roomData);
        }

        if($result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$roomData['name'].'房间保存成功');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'无法保存房间'.$roomData['name']);
        }
        return redirect()->route('school_manager.building.rooms',['uuid'=>$roomData['building_id']]);
    }
}
