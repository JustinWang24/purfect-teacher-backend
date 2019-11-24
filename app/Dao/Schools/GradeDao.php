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
use Illuminate\Support\Collection;

class GradeDao
{
    private $currentUser;

    /**
     * GradeDao constructor.
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
        return Grade::select(['id','name'])
            ->where('school_id',$schoolId)->where('name','like',$name.'%')->get();
    }

    /**
     * @param $id
     * @return Grade
     */
    public function getGradeById($id){
        return Grade::find($id);
    }

    /**
     * @param $id
     * @return Grade
     */
    public function getBySchool($id){
        return Grade::where('school_id',$id)->paginate();
    }

    /**
     * 根据给定的专业和年份获取班级
     * @param $majorId
     * @param $year
     * @param $field
     * @return Collection
     */
    public function getGradesByMajorAndYear($majorId, $year,$field='*'){
        return Grade::where('major_id',$majorId)
            ->where('year',$year)
            ->orderBy('name','asc')
            ->select($field)
            ->get();
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
