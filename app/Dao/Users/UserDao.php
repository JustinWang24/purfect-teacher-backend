<?php
/**
 * Created by Justin
 */

namespace App\Dao\Users;
use App\Dao\Teachers\TeacherProfileDao;
use App\Models\School;
use App\Models\Teachers\Teacher;
use App\Models\Users\GradeUser;
use App\User;
use App\Models\Acl\Role;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserDao
{
    private $protectedRoles = [Role::SUPER_ADMIN, Role::OPERATOR];

    public function createUser($data){
        return User::create($data);
    }

    /**
     * 根据用户的电话号码获取用户
     * @param string $mobile
     * @return User
     */
    public function getUserByMobile($mobile){
        return User::where('mobile',$mobile)->first();
    }

    /**
     * 根据用户的 id 或者 uuid 获取用户对象
     * @param string $idOrUuid
     * @return User|null
     */
    public function getUserByIdOrUuid($idOrUuid){
        if(is_string($idOrUuid) && strlen($idOrUuid) > 10){
            return $this->getUserByUuid($idOrUuid);
        }
        elseif ($idOrUuid){
            return $this->getUserById($idOrUuid);
        }
        return null;
    }

    /**
     * @param $idOrUuid
     * @return Teacher|null
     */
    public function getTeacherByIdOrUuid($idOrUuid){
        if(is_string($idOrUuid) && strlen($idOrUuid) > 10){
            return Teacher::where('uuid',$idOrUuid)->first();
        }
        elseif ($idOrUuid){
            return Teacher::find($idOrUuid);
        }
        return null;
    }

    /**
     * @param $uuid
     * @return User|null
     */
    public function getUserByUuid($uuid){
        return User::where('uuid',$uuid)->first();
    }

    /**
     * @param $id
     * @return User|null
     */
    public function getUserById($id){
        return User::find($id);
    }

    /**
     * @param $id
     * @return Teacher|null
     */
    public function getTeacherById($id){
        return Teacher::find($id);
    }

    /**
     * @param $uuid
     * @return Teacher|null
     */
    public function getTeacherByUuid($uuid){
        return Teacher::where('uuid',$uuid)->first();
    }

	/**
     * 获取用户所在班级
     * @param $uuid
     * @return
     */
    public function getUserGradeByUuid($uuid)
    {
        return User::where('uuid', $uuid)->with('gradeUser')->first();
    }

    /**
     * 获取用户的所有角色, 返回值为角色的 slug
     * @param User|int|string $user
     * @return string[]|null
     */
    public function getUserRoles($user){
        if(is_object($user)){
            return $user->getRoles();
        }else{
            $user = $this->getUserByIdOrUuid($user);
            return $user ? $user->getRoles() : null;
        }
    }

    /**
     * 给用户赋予一个角色
     * @param User $user
     * @param int|string $roleId
     * @param bool $ignoreProtectedRoles
     * @return bool
     */
    public function assignRoleToUser(User $user, $roleId, $ignoreProtectedRoles = false){
        if($ignoreProtectedRoles || !in_array($roleId, $this->protectedRoles)){
            // 不提供给用户赋予超级管理员和运营人员角色的功能
            $user->assignRole($roleId);
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 撤销用户的某个角色
     * @param User $user
     * @param int|string $roleId
     * @param bool $ignoreProtectedRoles
     * @return bool
     */
    public function revokeRoleFromUser(User $user, $roleId, $ignoreProtectedRoles = false){
        if($ignoreProtectedRoles || !in_array($roleId, $this->protectedRoles)){
            // 不提供给用户撤销 超级管理员和运营人员角色 的功能
            $user->revokeRole($roleId);
            return true;
        }
        else{
            return false;
        }
    }

    /**
     * 返回 APP 用户身份
     * @param $userType
     * @return string
     */
    public function  getUserRoleName($userType)
    {
        switch ($userType) {
            case Role::TEACHER :
                return trans('AppName.teacher');
                break;
            case Role::VERIFIED_USER_STUDENT :
                return trans('AppName.student');
                break;
            // todo :: 用到了再补充, 用来获取用户身份的名字
            default: return "" ;
                break;
        }
    }

    /**
     * 获取指定学校的所有的教师的列表
     * @param $schoolId
     * @param bool $simple: 简单的返回值 id=>name 的键值对组合
     * @return Collection
     */
    public function getTeachersBySchool($schoolId, $simple = false){
        if($simple){
            return GradeUser::select(DB::raw('user_id as id, name'))
                ->where('school_id',$schoolId)
                ->where('user_type',Role::TEACHER)
                ->get();
        }
        return GradeUser::where('school_id',$schoolId)
            ->where('user_type',Role::TEACHER)->get();
    }

    /**
     * 创建学校管理员账户
     * @param School $school
     * @param $mobile
     * @param $passwordInPlainText
     * @param $name
     * @param $email
     * @return MessageBag
     */
    public function createSchoolManager($school, $mobile, $passwordInPlainText,$name, $email){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try{
            $data = [
                'mobile'=>$mobile,
                'name'=>$name,
                'email'=>$email,
                'api_token'=>Uuid::uuid4()->toString(),
                'uuid'=>Uuid::uuid4()->toString(),
                'password'=>Hash::make($passwordInPlainText),
                'status'=>User::STATUS_VERIFIED,
                'type'=>Role::SCHOOL_MANAGER,
                'mobile_verified_at'=>Carbon::now(),
            ];
            $user = User::create($data);

            if($user){
                // 创建 grade user 的记录
                $gradeUserDao = new GradeUserDao();
                $gradeUserDao->addGradUser([
                    'user_id'=>$user->id,
                    'name'=>$name,
                    'user_type'=>Role::SCHOOL_MANAGER,
                    'school_id'=>$school->id,
                ]);
                // 创建他的资料账户
                $teacherProfileDao = new TeacherProfileDao();
                $teacherProfileDao->createProfile([
                    'uuid'=>Uuid::uuid4()->toString(),
                    'user_id'=>$user->id,
                    'school_id'=>$school->id,
                    'serial_number'=>'n.a',
                    'group_name'=>'管理',
                    'title'=>'易同学管理员',
                    'avatar'=>User::DEFAULT_USER_AVATAR,
                ]);
                DB::commit();
                $bag->setCode(JsonBuilder::CODE_SUCCESS);
            }else{
                $bag->setMessage('无法创建用户');
            }
        }
        catch (Exception $exception){
            DB::rollBack();
            $bag->setMessage($exception->getMessage());
        }
        return $bag;
    }

    /**
     * 更新用户的基本数据
     * @param $userId
     * @param null $mobile
     * @param null $password
     * @param null $name
     * @param null $email
     * @param null $niceName
     * @return mixed
     */
    public function updateUser($userId, $mobile=null, $password=null, $name=null, $email = null, $niceName=null){
        $data = [];

        if($mobile){
            $data['mobile'] = $mobile;
        }
        if($password){
            $data['password'] = Hash::make($password);
        }
        if($name){
            $data['name'] = $name;
        }
        if($mobile){
            $data['email'] = $email;
        }
        if($niceName) {
            $data['nice_name'] = $niceName;
        }
        if(!empty($data)){
            return User::where('id',$userId)->update($data);
        }
    }

    /**
     * excel导入用户时使用
     * @param $mobile
     * @param $name
     * @param $passwordInPlainText
     * @param int $type
     * @param int $status
     * @return mixed
     * @throws Exception
     */
    public function importUser($mobile,$name,$passwordInPlainText, $type = Role::VERIFIED_USER_STUDENT, $status=User::STATUS_VERIFIED)
    {
        $data = [
            'mobile'=>$mobile,
            'name'=>$name,
            'api_token'=>Uuid::uuid4()->toString(),
            'uuid'=>Uuid::uuid4()->toString(),
            'password'=>Hash::make($passwordInPlainText),
            'status'=>$status,
            'type'=>$type,
        ];
        return User::create($data);
    }

    /**
     * 更新 api_token
     * @param $userId
     * @param string $token
     * @return User
     * @throws Exception
     */
    public function updateApiToken($userId, $token = '')
    {
        return User::where('id', $userId)->update(['api_token' => $token]);
    }


}
