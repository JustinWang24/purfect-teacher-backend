<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 21/10/19
 * Time: 2:53 PM
 */

namespace App\Dao\Schools;
use App\User;
use App\Models\Users\GradeUser;
use App\Models\Schools\Grade;

class GradeDao
{
    private $currentUser;
    public function __construct(User $user)
    {
        $this->currentUser = $user;
    }

    /**
     * @param $id
     * @return Grade
     */
    public function getGradeById($id){
        return Grade::find($id);
    }

    /**
     * 创建班级
     * @param $data
     * @return Grade
     */
    public function createGrade($data){
        $data['last_updated_by'] = $this->currentUser->id;
        return Grade::create($data);
    }

    /**
     * 更新班级的数据
     * @param $data
     * @param null $where
     * @param null $whereValue
     * @return mixed
     */
    public function updateGrade($data, $where = null, $whereValue = null){
        $id = $data['id'];
        $data['last_updated_by'] = $this->currentUser->id;
        unset($data['id']);
        if($whereValue && $where){
            return Grade::where($where,$whereValue)->update($data);
        }
        return Grade::where('id',$id)->update($data);
    }
}