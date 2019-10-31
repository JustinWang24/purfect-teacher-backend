<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 9:20 PM
 */

namespace App\Dao\Users;
use App\Models\Users\GradeUser;
use Illuminate\Database\Eloquent\Collection;
use App\User;
use Illuminate\Support\Facades\DB;

class GradeUserDao
{
    private $currentUser;

    public function __construct($user = null)
    {
        $this->currentUser = $user;
    }

    /**
     * 模糊查找用户的信息
     * @param $name
     * @param $schoolId
     * @return Collection
     */
    public function getUsersWithNameLike($name, $schoolId){
        return GradeUser::select(['id','name','user_type','department_id','major_id','grade_id'])
            ->where('school_id',$schoolId)
            ->where('name','like',$name.'%')
            ->take(12)->get();
    }

    /**
     * 获取用户的学校信息
     * @param null $userId
     * @return \Illuminate\Support\Collection
     */
    public function getSchoolsId($userId = null){
        if(is_null($userId)){
            $userId = $this->currentUser->id;
        }
        return DB::table('grade_users')->select('school_id')->where('user_id',$userId)->get();
    }

    /**
     * 根据学校获取 id
     * @param $schoolId
     * @param $types
     * @return User
     */
    public function getBySchool($schoolId,$types){
        return GradeUser::where('school_id',$schoolId)->whereIn('user_type',$types)->paginate();
    }

    /**
     * 根据给定的校园 id 值, 获取用户信息
     * @param $campusId
     * @param $type
     * @return Collection
     */
    public function paginateUserByCampus($campusId, $type){
        return $this->_paginateUsersBy($type,'campus_id', $campusId);
    }

    /**
     * 根据给定的学院 id 值, 获取用户信息
     * @param $id
     * @param $type
     * @return Collection
     */
    public function paginateUserByInstitute($id, $type){
        return $this->_paginateUsersBy($type,'institute_id', $id);
    }

    /**
     * 根据给定的系 id 值, 获取用户信息
     * @param $id
     * @param $type
     * @return Collection
     */
    public function paginateUserByDepartment($id, $type){
        return $this->_paginateUsersBy($type,'department_id', $id);
    }

    /**
     * 根据给定的班级 id 值, 获取用户信息
     * @param $id
     * @param $type
     * @return Collection
     */
    public function paginateUserByGrade($id, $type){
        return $this->_paginateUsersBy($type,'grade_id', $id);
    }

    /**
     * 根据给定的学生专业 id 值, 获取用户信息
     * @param $id
     * @param $type
     * @return Collection
     */
    public function paginateUserByMajor($id, $type){
        return $this->_paginateUsersBy($type,'major_id', $id);
    }

    /**
     * @param $type
     * @param $fieldName
     * @param $fieldValue
     * @param string $orderBy
     * @param string $direction
     * @return Collection
     */
    private function _paginateUsersBy($type, $fieldName, $fieldValue, $orderBy = 'updated_at', $direction = 'desc'){
        if(is_array($type)){
            return GradeUser::where($fieldName,$fieldValue)
                ->whereIn('user_type',$type)
                ->orderBy($orderBy,$direction)->paginate();
        }
        return GradeUser::where($fieldName,$fieldValue)
            ->where('user_type',$type)
            ->orderBy($orderBy,$direction)->paginate();
    }
}