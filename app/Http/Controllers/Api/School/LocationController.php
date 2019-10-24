<?php

namespace App\Http\Controllers\Api\School;

use App\Dao\Schools\RoomDao;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolDao;
use App\User;
use App\Dao\Schools\BuildingDao;

class LocationController extends Controller
{
    /**
     * 获取建筑物列表, 根据校区分组了
     * @param Request $request
     * @return string
     */
    public function load_buildings(Request $request){
        $schoolId = $request->get('school');
        $user = new User();
        if(strlen($schoolId) > 10){
            $schoolDao = new SchoolDao($user);
            $school = $schoolDao->getSchoolByIdOrUuid($schoolId);
            if($school){
                $schoolId = $school->id;
            }else{
                $schoolId = null;
            }
        }

        if($schoolId){
            $buildingDao = new BuildingDao($user);
            return JsonBuilder::Success(['campuses'=>$buildingDao->getBuildingsBySchool($schoolId)]);
        }
        return JsonBuilder::Error();
    }

    /**
     * 获取建筑物的房间列表
     * @param Request $request
     * @return string
     */
    public function load_building_rooms(Request $request){
        $buildingId = $request->get('building');
        $dao = new RoomDao(new User());
        return JsonBuilder::Success(['rooms'=>$dao->getRoomsByBuilding($buildingId)]);
    }
}
