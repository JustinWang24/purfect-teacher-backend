<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 8:37 AM
 */

namespace App\Dao\Schools;
use App\Models\Schools\DepartmentAdviser;
use App\User;
use App\Models\Schools\Department;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Database\Eloquent\Collection;

class DepartmentDao
{
    private $currentUser;

    /**
     * DepartmentDao constructor.
     * @param User|null $user
     */
    public function __construct($user = null)
    {
        $this->currentUser = $user;
    }

    /**
     * @param $name
     * @param $schoolId
     * @return Collection
     */
    public function searchByName($name, $schoolId){
        return Department::select(['id','name'])
            ->where('school_id',$schoolId)->where('name','like',$name.'%')->get();
    }

    /**
     * 根据给定的 campus 或 id 获取包含的学院
     * @param $institute
     * @return Collection
     */
    public function getByInstitute($institute){
        if(is_object($institute)){
            $institute = $institute->id;
        }
        return Department::where('institute_id',$institute)->get();
    }

    /**
     * 根据给定的 campus 或 id 获取包含的学院
     * @param $schoolId
     * @return Collection
     */
    public function getBySchool($schoolId){
        return Department::where('school_id',$schoolId)->paginate();
    }

    /**
     * @param $id
     * @return Department|null
     */
    public function getDepartmentById($id){
        return Department::find($id);
    }

    /**
     * 创建
     * @param $data
     * @return Department
     */
    public function createDepartment($data){
        $data['last_updated_by'] = $this->currentUser->id;
        return Department::create($data);
    }

    /**
     * @param $data
     * @param null $where
     * @param null $whereValue
     * @return mixed
     */
    public function updateDepartment($data, $where=null, $whereValue=null){
        $data['last_updated_by'] = $this->currentUser->id;
        $id = $data['id'];
        unset($data['id']);
        if($where && $whereValue){
            return Department::where($where,$whereValue)->update($data);
        }
        return Department::where('id',$id)->update($data);
    }


    /**
     * @param $schoolId
     * @param $field
     * @return mixed
     */
    public function getDepartmentBySchoolId($schoolId,$field='*')
    {
        return Department::where('school_id',$schoolId)->select($field)->get();
    }

    public function getByName($name, $schoolId, $campusId, $instituteId){
        return Department::where('school_id',$schoolId)->where('campus_id',$campusId)->
                           where('institute_id',$instituteId)->where('name', $name)->first();
    }

    /**
     * @param $data
     * @return MessageBag
     */
    public function setAdviser($data){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);

        try{
            if(empty($data['id'])){
                // 创建
                DepartmentAdviser::create($data);
            }
            else{
                DepartmentAdviser::where('id',$data['id'])->update($data);
            }
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
        }
        catch (\Exception $exception){
            $bag->setMessage($exception->getMessage());
        }
        return $bag;
    }
}
