<?php

namespace App\Dao\FacilityManage;

use App\Dao\Schools\BuildingDao;
use App\Dao\Schools\CampusDao;
use App\Dao\Schools\RoomDao;
use App\Models\Schools\Facility;
use Illuminate\Support\Collection;

class FacilityDao
{


    /**
     * 保存和修改
     * @param $data
     * @return mixed
     */
    public function save($data) {

        if(!empty($data['id'])) {
            $id = $data['id'];
            unset($data['id']);
            $result = Facility::where('id',$id)->update($data);
        } else {
            $result = Facility::create($data);
        }
        return $result;
    }


    /**
     * 获取分页
     * @param $map
     * @return mixed
     */
    public function getFacilityPage($map) {
        $result = Facility::where($map)->with(['campus','room','building'])->paginate(10);
        return $result;
    }

    /**
     * 获取详情
     * @param $map
     * @return mixed
     */
    public function getFacilityInfo($map) {
        return Facility::where($map)->first();
    }


    /**
     * 设备编辑的详细信息
     * @param $id
     * @param $user
     * @param $schoolId
     * @return array
     */
    public function facilityInfoDispose($id,$user,$schoolId) {
        $campusDao = new CampusDao($user);
        $buildingDao = new BuildingDao($user);
        $roomDao = new RoomDao($user);
        $facility = $this->getFacilityInfo(['id'=>$id]);
        $field = ['id', 'name'];
        $campus = $campusDao->getCampusesBySchool($schoolId,$field);
        $building = $buildingDao->getBuildingByCampusId($facility['campus_id']);
        $room = $roomDao->getRoomByBuildingId($facility['building_id']);

        return ['facility'=>$facility, 'campus'=>$campus,
            'building'=>$building, 'room'=>$room];
    }


    /**
     * 删除
     * @param $id
     * @return mixed
     */
    public function delete($id) {
        return Facility::where('id',$id)->delete();
    }


    /**
     * 获取类型
     * @return array
     */
    public function getType(){
        return [
            ['id'=> Facility::TYPE_MONITORING, 'val'=> Facility::TYPE_MONITORING_TEXT],
            ['id'=> Facility::TYPE_ENTRANCE_GUARD, 'val'=> Facility::TYPE_ENTRANCE_GUARD_TEXT],
            ['id'=> Facility::TYPE_CLASS_SIGN, 'val'=> Facility::TYPE_CLASS_SIGN_TEXT],
            ['id'=> Facility::TYPE_CLASS_CLASSROOM, 'val'=> Facility::TYPE_CLASS_CLASSROOM_TEXT],
        ];
    }

    /**
     * 根据设备编号获取
     * @param $number
     * @return Facility
     */
    public function getFacilityByNumber($number)
    {
        return Facility::where('facility_number', $number)
            ->where('status', Facility::STATUS_OPEN)
            ->first();
    }

    /**
     * 根据布放位置获取设备列表
     * @param $schoolId
     * @param $location
     * @return Collection
     */
    public function getFacilityByLocation($schoolId, $location){
        return Facility::where('location', $location)
            ->where('status', Facility::STATUS_OPEN)
            ->where('school_id', $schoolId)
            ->get();
    }
}
