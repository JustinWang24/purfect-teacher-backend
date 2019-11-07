<?php


namespace App\Dao\RecruitStudent;

use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\Models\Schools\RecruitmentPlan;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
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
     * @param $simple: 是否只获取简单数据
     * @return mixed
     */
    public function getInformaticsByUserId($userId, $simple = false)
    {
        if($simple){
            return RegistrationInformatics::select(['id','recruitment_plan_id','status','note'])
                ->where('user_id', $userId)->get();
        }
        return RegistrationInformatics::where('user_id', $userId)->get();
    }

    /**
     * 根据userId 和 招生计划的 id 获取报名信息
     * @param $userId
     * @param $planId
     * @param bool $simple
     * @return RegistrationInformatics|null
     */
    public function getInformaticsByUserIdAndPlanId($userId, $planId, $simple = false){
        if($simple){
            return RegistrationInformatics::select(['id','recruitment_plan_id','status','note'])
                ->where('user_id', $userId)
                ->where('recruitment_plan_id', $planId)
                ->first();
        }
        return RegistrationInformatics::where('user_id', $userId)
            ->where('recruitment_plan_id', $planId)
            ->first();
    }


    /**
     * 添加未认证用户并且报名
     * @param $data
     * @param RecruitmentPlan $plan
     * @throws Exception
     * @return IMessageBag
     */
    public function addUser($data, $plan)
    {
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['password'] = Hash::make('000000');
        $data['type'] = Role::VISITOR;
        DB::beginTransaction();

        $user =  User::create($data);

        $bag = new MessageBag();
        $profile = false;

        if ($user) {
            $userProfile = $data;
            $userProfile['uuid'] = $data['uuid'];
            $userProfile['user_id'] = $user->id;
            $userProfile['device']  = 0;
            $userProfile['year'] = $plan->year; // 这个应该是从招生中的入学年级来
            $userProfile['serial_number'] = 0;  // 学号还无法分配
            $userProfile['avatar'] = '/assets/img/dp.jpg'; // 用户默认的图片
            $profile = StudentProfile::create($userProfile);
            if(!$profile){
                $bag->setMessage('学生档案创建失败');
            }
        } else {
            $user = false;
            $bag->setMessage('学生用户账户创建失败');
        }

        if ($user && $profile) {
            DB::commit();
            $bag->setData([
                'user'=>$user,
                'profile'=>$profile
            ]);
        } else {
            DB::rollBack();
            $bag->setCode(JsonBuilder::CODE_ERROR);
        }
        return $bag;
    }

    /**
     * 报名: 用户在报名的时候, 需要完成的操作包括
     * 1: 添加报名表
     * 2: 更新招生广告的 "已报名学生数" 字段
     * @param $data
     * @param User $user
     * @return IMessageBag
     */
    public function signUp($data, $user)
    {
        $data['user_id'] = $user->id;
        $msgBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        $reg =  RegistrationInformatics::create($data);
        if($reg){
            // 报名数据添加成功
            $planDao = new RecruitmentPlanDao($reg->school_id);
            $result = $planDao->increaseAppliedCountNumber($reg->recruitment_plan_id);
            if($result){
                // 自增操作完成
                DB::commit();
                $msgBag->setCode(JsonBuilder::CODE_SUCCESS);
                $msgBag->setData($result);
            }else{
                $msgBag->setMessage('无法增加总报名人数的记录');
            }
        }else{
            $msgBag->setMessage('无法添加报名表');
        }

        if(!$msgBag->isSuccess()){
            DB::rollBack();
        }
        return $msgBag;
    }



    /**
     * 通过计划ID和状态获取条数 状态值判断是大于等于
     * @param int   $status  状态
     * @param array $planIdArr  计划ID
     * @return mixed
     */
    public function getCountByStatusAndPlanIdArr($status,$planIdArr) {
        return RegistrationInformatics::where('status','>=',$status)->
        whereIn('recruitment_plan_id',$planIdArr)->count();
    }


}
