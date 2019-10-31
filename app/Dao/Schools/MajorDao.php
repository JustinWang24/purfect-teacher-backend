<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 9:05 AM
 */

namespace App\Dao\Schools;
use App\User;
use App\Models\Schools\Major;
use Illuminate\Database\Eloquent\Collection;

class MajorDao
{
    private $currentUser;

    /**
     * MajorDao constructor.
     * @param User|null $user
     */
    public function __construct($user = null)
    {
        $this->currentUser = $user;
    }

    /**
     * 根据给定的 campus 或 id 获取包含的学院
     * @param $department
     * @param $field
     * @return Collection
     */
    public function getByDepartment($department,$field='*'){
        if(is_object($department)){
            $department = $department->id;
        }
        return Major::where('department_id',$department)->select($field)->get();
    }

    /**
     * @param $id
     * @return Major|null
     */
    public function getMajorById($id){
        return Major::find($id);
    }

    /**
     * @param $schoolId
     * @param bool $simple
     * @return Collection
     */
    public function getMajorsBySchool($schoolId, $simple = true){
        if($simple)
            return Major::select(['id','name'])->where('school_id',$schoolId)->orderBy('name','asc')->get();
        return Major::where('school_id',$schoolId)->orderBy('name','asc')->get();
    }

    /**
     * 创建新的专业
     * @param $majorData
     * @return Major
     */
    public function createMajor($majorData){
        $majorData['last_updated_by'] = $this->currentUser->id;
        return Major::create($majorData);
    }

    /**
     * 更新专业的数据
     * @param $majorData
     * @return Major
     */
    public function updateMajor($majorData){
        $id = $majorData['id'];
        $majorData['last_updated_by'] = $this->currentUser->id;
        unset($majorData['id']);
        return Major::where('id',$id)->update($majorData);
    }
    
    /**
     * 分页获列表
     * @param $map
     * @param string $field
     * @return mixed
     */
    public function getMajorPage($map,$field='*') {
        return Major::where($map)->select($field)->paginate(15);
    }
}
