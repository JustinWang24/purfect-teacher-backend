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
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;

class BuildingDao
{
    /**
     * @var User $currentUser
     */
    private $currentUser;
    public function __construct($user = null)
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


    public function getBuildingByCode($schoolId, $code) {
        return Building::where('school_id', $schoolId)
            ->where('description', $code)
            ->first();
    }


    /**
     * @param $data
     * @return MessageBag
     */
    public function createBuilding($data){
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        $building = $this->getBuildingByCode($data['school_id'], $data['description']);
        if(!empty($building)) {
            $messageBag->setMessage('建筑编号已存在');
            return $messageBag;
        }
        $result = Building::create($data);
        if($result) {
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setMessage('创建成功');
        } else {
            $messageBag->setMessage('创建失败');
        }
        return $messageBag;
    }

    /**
     * 更新 building 数据
     * @param $data
     * @return MessageBag
     */
    public function updateBuilding($data){
        $id = $data['id'];
        unset($data['id']);
        $map = [
            ['school_id','=',$data['school_id']],
            ['id', '<>', $id],
            ['description','=',$data['description']]
        ];
        $info = Building::where($map)->first();
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        if(!empty($info)) {
            $messageBag->setMessage('建筑编号已存在');
            return $messageBag;
        }

        $result = Building::where('id', $id)->update($data);
        if($result !== false) {
            $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            $messageBag->setMessage('编辑成功');
        } else {
            $messageBag->setMessage('编辑失败');
        }
        return $messageBag;
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
                    'id'=>$campus->id,
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
     * @param int $type
     * @return mixed
     */
    public function getBuildingByCampusId($campusId, $type = null) {
        $field = ['id', 'campus_id', 'name', 'type', 'description'];
        $map = ['campus_id'=>$campusId];
        if($type){
            $map['type']=$type;
        }
        $result = $this->getBuildingList($map,$field);
        return $result;
    }
}
