<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 11:47 AM
 */

namespace App\Dao\Schools;
use App\Models\Schools\Campus;
use App\User;
use Illuminate\Support\Collection;

class CampusDao
{
    private $currentUser;
    public function __construct($user = null)
    {
        $this->currentUser = $user;
    }

    /**
     * 创建操作
     * @param $data
     * @return mixed
     */
    public function createCampus($data){
        $data['last_updated_by'] = $this->currentUser->id;
        return Campus::create($data);
    }

    /**
     * @param $name
     * @param $schoolId
     * @return Collection
     */
    public function searchByName($name, $schoolId){
        return Campus::select(['id','name'])
            ->where('school_id',$schoolId)
            ->where('name','like',$name.'%')
            ->get();
    }

    /**
     * 更新操作
     * @param $data
     * @return mixed
     */
    public function updateCampus($data){
        $data['last_updated_by'] = $this->currentUser->id;
        $id = $data['id'];
        unset($data['id']);
        return Campus::where('id',$id)->update($data);
    }

    /**
     * 根据 id 获取校区对象
     * @param $id
     * @param $schoolId
     * @return mixed
     */
    public function getCampusById($id, $schoolId = null){
        if($schoolId){
            return Campus::where('id',$id)->where('school_id',$schoolId)->first();
        }
        return Campus::find($id);
    }

    /**
     * 根据学校 ID 获取所有的校区
     * @param $schoolId
     * @param $field
     * @return Collection
     */
    public function getCampusesBySchool($schoolId,$field='*'){
        return Campus::where('school_id',$schoolId)->select($field)->get();
    }
}
