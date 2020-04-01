<?php

namespace App\Http\Controllers\Operator;

use App\Http\Requests\School\BuildingRequest;
use App\Http\Controllers\Controller;
use App\Models\Schools\Building;
use App\Dao\Schools\BuildingDao;
use App\Utils\FlashMessageBuilder;
use App\Dao\Schools\CampusDao;

class BuildingsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 查看教学楼的教室
     * @param BuildingRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function rooms(BuildingRequest $request){
        $dao = new BuildingDao($request->user());
        $building = $dao->getBuildingById($request->uuid());
        $this->dataForView['building'] = $building;
        $this->dataForView['rooms'] = $building->rooms;
        return view('school_manager.school.rooms', $this->dataForView);
    }

    /**
     * 加载添加建筑物的表单
     * @param BuildingRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(BuildingRequest $request){
        $dao = new CampusDao($request->user());
        $type = $request->get('type');
        $this->dataForView['type'] = $type;
        $this->dataForView['campus'] = $dao->getCampusById($request->uuid());
        $this->dataForView['building'] = new Building();
        return view('school_manager.building.add', $this->dataForView);
    }

    /**
     * 加载添加校区的表单
     * @param BuildingRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BuildingRequest $request){
        $dao = new BuildingDao($request->user());
        $building = $dao->getBuildingById($request->uuid());
        $this->dataForView['building'] = $building;
        $this->dataForView['campus'] = $building->campus;
        return view('school_manager.building.edit', $this->dataForView);
    }

    /**
     * 保存建筑的方法
     * @param BuildingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(BuildingRequest $request){
        $buildingData = $request->get('building');
        $buildingData['school_id'] = $request->session()->get('school.id');
        $buildingDao = new BuildingDao($request->user());

        if(isset($buildingData['id'])){
            $result = $buildingDao->updateBuilding($buildingData);
        }
        else{
            $result = $buildingDao->createBuilding($buildingData);
        }

        $msg = $result->getMessage();
        if($result->isSuccess()){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$msg);
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,$msg);
        }
        return redirect()->route('school_manager.campus.buildings',['uuid'=>$buildingData['campus_id'],'type'=>$buildingData['type']]);
    }
}
