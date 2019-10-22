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
    public function __construct(User $user)
    {
        $this->currentUser = $user;
    }

    /**
     * 根据给定的 campus 或 id 获取包含的学院
     * @param $department
     * @return Collection
     */
    public function getByDepartment($department){
        if(is_object($department)){
            $department = $department->id;
        }
        return Major::where('department_id',$department)->get();
    }

    /**
     * @param $id
     * @return Major|null
     */
    public function getMajorById($id){
        return Major::find($id);
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
}