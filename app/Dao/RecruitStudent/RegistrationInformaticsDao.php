<?php


namespace App\Dao\RecruitStudent;

use App\Models\RecruitStudent\RegistrationInformatics;
use Exception;
use Ramsey\Uuid\Uuid;
use App\Models\Students\StudentProfile;
use Illuminate\Support\Facades\DB;
use App\Models\Acl\Role;
use App\User;
use Illuminate\Support\Facades\Hash;

class RegistrationInformaticsDao
{

    /**
     * 报名列表
     * @param $field
     * @param array $where
     * @param $order
     * @return object
     */
    public function getRegistrationInformatics($field, $where = [], $order)
    {
        $data = RegistrationInformatics::select($field)->where($where)
                ->with([
                'studentProfile' => function($query) {
                   $query->select('user_id', 'gender', 'nation_name', 'political_name',
                    'source_place', 'parent_name', 'parent_mobile', 'birthday');
                },
                'school' => function($query) {
                    $query->select('id', 'name');
                },
                'major' => function($query) {
                    $query->select('id', 'name');
                }])
                ->orderBy('created_at', 'desc')
                ->orderBy('relocation_allowed', $order)
                ->paginate(RegistrationInformatics::PAGE_NUMBER);

        return $data;
    }

    /**
     * 获取一条报名详情
     * @param $field
     * @param $id
     * @return object
     */
    public function getOneDataInfoById($field, $id)
    {
        $data = RegistrationInformatics::select($field)->where('id', $id)
                ->with([
                'user' => function($query) {
                    $query->select('id', 'mobile', 'email');
                },
                'studentProfile' => function($query) {
                   $query->select('user_id', 'gender', 'nation_name', 'political_name',
                       'source_place', 'parent_name', 'parent_mobile', 'birthday', 'id_number',
                       'country', 'qq', 'wx', 'state', 'city', 'area', 'examination_score',
                       'detailed_address'
                   );
                },
                'school' => function($query) {
                    $query->select('id', 'name');
                },
                'major' => function($query) {
                    $query->select('id', 'name');
                }])
                ->first();

        return $data;
    }

    /**
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data)
    {
        return RegistrationInformatics::where('id', $id)->update($data);
    }

    /**
     * 根据userId获取报名信息
     * @param $userId
     * @return mixed
     */
    public function getInformaticsByUserId($userId)
    {
        return RegistrationInformatics::where('user_id', $userId)->get();
    }


    /**
     * 添加未认证用户并且报名
     * @param $data
     * @throws Exception
     * @return void|bool
     */
    public function addUser($data)
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['password'] = Hash::make('000000');
        $data['type'] = Role::VISITOR;
        DB::beginTransaction();

        $user =  User::create($data);

        if ($user) {
            $userProfile = $data;
            $userProfile['uuid'] = $data['uuid'];
            $userProfile['user_id'] = $user->id;
            $userProfile['device']  = 0;
            $userProfile['year'] = date('Y');
            $userProfile['serial_number'] = 0;
            $userProfile['avatar'] = 'www.tx.test';
            $profile = StudentProfile::create($userProfile);
        } else {
            $user = false;
            $profile = false;
        }

        if ($user == false || $profile == false) {
            DB::rollBack();
            $result = false;
        } else {
            DB::commit();
            $result = $user->id;
        }
        return $result;
    }

    /**
     * 报名
     * @param $data
     * @return mixed
     */
    public function signUp($data)
    {
       return RegistrationInformatics::create($data);
    }


}
