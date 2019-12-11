<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 22/10/19
 * Time: 11:03 AM
 */

namespace App\Dao\Schools;
use App\Models\Schools\Building;
use App\Models\Schools\Campus;
use App\User;

class BuildingDao
{
    private $currentUser;
    public function __construct(User $user)
    {
        $this->currentUser = $user;
    }

    /**
     * @param $id
     * @return Building
     */
    public function getBuildingById($id){
        return Building::find($id);
    }

    /**
     * @param $data
     * @return Building
     */
    public function createBuilding($data){
        return Building::create($data);
    }

    /**
     * 更新 building 数据
     * @param $data
     * @param null $where
     * @param null $whereValue
     * @return mixed
     */
    public function updateBuilding($data, $where = null, $whereValue = null){
        $id = $data['id'];
        unset($data['id']);
        if($where && $whereValue){
            return Building::where($where, $whereValue)->update($data);
        }
        return Building::where('id', $id)->update($data);
    }

    /**
     * @param int $type
     * @param Campus $campus
     * @return mixed
     */
    public function getBuildingsByType($type, $campus){
        if($type === Building::TYPE_CLASSROOM_BUILDING){
            return $campus->classroomBuildings;
        }
        elseif($type === Building::TYPE_STUDENT_HOSTEL_BUILDING){
            return $campus->hostels;
        }
        elseif ($type === Building::TYPE_HALL){
            return $campus->halls;
        }
        else{
            return $campus->buildings;
        }
    }

    public function getBuildingsByCampus($campus){
        return Building::where('campus_id', is_object($campus) ? $campus->id : $campus)->orderBy('name','asc')->get();
    }

    /**
     * @param int $schoolId
     * @return mixed
     */
    public function getBuildingsBySchool($schoolId){
        $dao = new CampusDao(new User());
        $campuses = $dao->getCampusesBySchool($schoolId);
        $data = [];
        foreach ($campuses as $campus) {
            $buildings = $this->getBuildingsByCampus($campus);
            if(count($buildings)){
                $data[] = [
                    'campus'=>$campus->name,
                    'buildings'=>$buildings
                ];
            }
        }
        return $data;
    }


    /**
     * 获取列表
     * @param $map
     * @param $field
     * @return mixed
     */
    protected function getBuildingList($map,$field) {
        return Building::where($map)->select($field)->get();
    }


    /**
     * 根据校区id获取建筑
     * @param $campusId
     * @return mixed
     */
    public function getBuildingByCampusId($campusId) {
        $field = ['id', 'campus_id', 'name', 'type', 'description'];
        $map = ['campus_id'=>$campusId,'type'=>Building::TYPE_CLASSROOM_BUILDING];
        $result = $this->getBuildingList($map,$field);
        return $result;
    }
}
