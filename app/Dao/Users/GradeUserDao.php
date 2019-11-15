<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 18/10/19
 * Time: 9:20 PM
 */

namespace App\Dao\Users;
use App\Models\Acl\Role;
use App\Models\Users\GradeUser;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Database\Eloquent\Collection;
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
     * @param $userType : 需要限定的用户类型
     * @return Collection
     */
    public function getUsersWithNameLike($name, $schoolId, $userType = null){
        $where = [
            ['school_id','=',$schoolId],
            ['name','like',$name.'%'],
        ];
        $query = GradeUser::select(['id','user_id','name','user_type','department_id','major_id','grade_id'])
            ->where($where);

        if($userType){
            if(is_array($userType)){
                // 如果同时定位多个角色
                $query->whereIn('user_type',$userType);
            }
            else{
                $query->where('user_type',$userType);
            }
        }

        return $query->take(ConfigurationTool::DEFAULT_PAGE_SIZE_QUICK_SEARCH)->get();
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
     * @return Collection
     */
    public function getBySchool($schoolId,$types){
        return GradeUser::where('school_id',$schoolId)->whereIn('user_type',$types)->paginate();
    }

    /**
     * 根据学校 id 和 用户 id 来检查是否存在
     * @param $schoolId
     * @param $userId
     * @param $simple: 简单数据即可
     * @return GradeUser
     */
    public function isUserInSchool($userId, $schoolId, $simple = true){
        $query = GradeUser::where('school_id',$schoolId)->where('user_id',$userId);
        if($simple){
            $query->select('name');
        }
        return $query->first();
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

    /**
     * 获取指定学校的第一位老师
     *
     * @param $schoolId
     * @return GradeUser
     */
    public function getAnyTeacher($schoolId){
        return GradeUser::where('school_id',$schoolId)->where('user_type',Role::TEACHER)->first();
    }

    /**
     * 获取班级通讯录
     * @param $gradeId
     * @return array
     */
    public function getGradeAddressBook($gradeId)
    {
        $userDao = new UserDao;
        $data = GradeUser::where('grade_id', $gradeId)->select('user_id', 'user_type')->with([
                    'user' => function ($query) {
                        $query->select('id', 'name', 'mobile');
                    }
                ])->get();
        $teacher = [];
        $student = [];

        foreach ($data as $key => $val) {
            if ($val['user_type'] == Role::TEACHER) {
                $teacher[$key]['name'] = $val['user']['name'];
                $teacher[$key]['tel']  = $val['user']['mobile'];
                $teacher[$key]['type'] = $userDao->getUserRoleName($val['user_type']);
            } elseif ($val['user_type'] == Role::VERIFIED_USER_STUDENT) {
                $student[$key]['name'] = $val['user']['name'];
                $student[$key]['tel']  = $val['user']['mobile'];
                $student[$key]['type'] = $userDao->getUserRoleName($val['user_type']);
            }
        }

        return ['schoolmate_list'=>array_merge($student),  'teacher_list'=>array_merge($teacher)];

    }

    /**
     * 根据学校ID 获取所有学生
     * @param $schoolId
     * @return GradeUser
     */
    public function getAllStudentBySchoolId($schoolId)
    {
        return GradeUser::where(['school_id' =>  $schoolId], ['user_type' => Role::VERIFIED_USER_STUDENT])->get();
    }

    /**
     * 插入多条用户班级关系
     * @param $data
     * @return bool
     */
    public function addGradUser($data)
    {
        return DB::table('grade_users')->insert($data);
    }

    /**
     * 根据用户ID获取用户信息
     * @param $userId
     * @return GradeUser
     */
    public function getUserInfoByUserId($userId)
    {
        return GradeUser::where('user_id', $userId)->first();
    }

}
