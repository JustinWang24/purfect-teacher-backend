<?php


namespace App\Dao\RecruitStudent;

use App\Dao\Schools\GradeDao;
use App\Dao\RecruitmentPlan\RecruitmentPlanDao;
use App\Dao\Users\GradeUserDao;
use App\Models\RecruitStudent\RegistrationInformatics;
use App\Models\Schools\Grade;
use App\Models\Schools\RecruitmentPlan;
use App\Utils\JsonBuilder;
use App\Utils\Misc\ConfigurationTool;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Ramsey\Uuid\Uuid;
use App\Models\Users\GradeUser;
use App\Models\Students\StudentProfile;
use Illuminate\Support\Facades\DB;
use App\Models\Acl\Role;
use App\User;
use Illuminate\Support\Facades\Hash;

class RegistrationInformaticsDao
{
    /**
     * 获取待批准的报名表
     * @param $planId
     * @return mixed
     */
    public function getPaginatedRegistrationsWaitingForApprovalByPlanId($planId){
        return RegistrationInformatics::where('recruitment_plan_id',$planId)
            ->where('status','<=',RegistrationInformatics::WAITING)
            ->orderBy('updated_at','desc')
            ->paginate();
    }

    /**
     * 获取被录取的新生的列表
     * @param $planId
     * @return mixed
     */
    public function getPaginatedApprovedByPlanId($planId){
        return RegistrationInformatics::where('recruitment_plan_id',$planId)
            ->where('status',RegistrationInformatics::APPROVED)
            ->orderBy('updated_at','desc')
            ->paginate();
    }

    /**
     * 获取待批准的报名表
     * @param $planId
     * @return mixed
     */
    public function getPaginatedRegistrationsRefusedByPlanId($planId){
        return RegistrationInformatics::where('recruitment_plan_id',$planId)
            ->where('status',RegistrationInformatics::REFUSED)
            ->orderBy('updated_at','desc')
            ->paginate();
    }

    /**
     * 获取待录取的报名表
     * @param $planId
     * @return mixed
     */
    public function getPaginatedPassedByPlanIdForApproval($planId){
        return RegistrationInformatics::where('recruitment_plan_id',$planId)
            ->where('status',RegistrationInformatics::PASSED)
            ->orderBy('updated_at','desc')
            ->paginate();
    }

    /**
     * 获取所有的报名表
     * @param $planId
     * @return mixed
     */
    public function getPaginatedByPlanIdForAll($planId){
        return RegistrationInformatics::where('recruitment_plan_id',$planId)
            ->orderBy('updated_at','desc')
            ->paginate();
    }

    /**
     * @param $name
     * @param $schoolId
     * @return \Illuminate\Support\Collection
     */
    public function searchRegistrationFormsByStudentName($name, $schoolId){
        return RegistrationInformatics::where('school_id',$schoolId)
            ->where('name','like','%'.$name.'%')
            ->take(ConfigurationTool::DEFAULT_PAGE_SIZE)
            ->get();
    }

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
                ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);

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
     * @return Collection
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
                ->orderBy('id','desc')
                ->first();
        }
        return RegistrationInformatics::where('user_id', $userId)
            ->where('recruitment_plan_id', $planId)
            ->orderBy('id','desc')
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
            $userProfile['api_token'] = $data['uuid'];
            $userProfile['user_id'] = $user->id;
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
     * 修改报名基础信息
     * @param $userId /用户id
     * @param $data
     * @param RecruitmentPlan $plan
     * @throws Exception
     * @return IMessageBag
     */
    public function eidtUser($user , $data, $plan)
    {
        if (empty($user)) return false;

        // 更新用户信息
        $userSave['name'] = $data['name']; // 姓名
        $userSave['email'] = $data['email']; // 邮箱
        User::where('id',$user->id)->update($userSave);

        // 更新基础信息
        $userProfile['uuid'] = Uuid::uuid4()->toString();
        $userProfile['user_id'] = $user->id;
        $userProfile['year'] = $plan->year; // 这个应该是从招生中的入学年级来
        // $userProfile['serial_number'] = 0;  // 学号还无法分配
        $userProfile['avatar'] = '/assets/img/dp.jpg'; // 用户默认的图片
        $userProfile['gender'] = $data['gender']; // 性别
        $userProfile['id_number'] = $data['id_number']; // 身份证号码
        // $userProfile['nation_code'] = $data['nation_code']; // TODO...名族代码不知道传啥
        $userProfile['nation_name'] = $data['nation_name']; // 名族名称
        //$userProfile['political_code'] = $data['political_code']; // TODO...政治面貌code 不知道传啥
        $userProfile['political_name'] = $data['political_name']; // 政治面貌名称
        $userProfile['source_place'] = $data['source_place']; // 生源地
        $userProfile['country'] = $data['country']; // 籍贯
        $userProfile['contact_number'] = $data['mobile']; // 联系电话
        $userProfile['birthday'] = $data['birthday']; // 出身年月
        $userProfile['qq'] = $data['qq']; // QQ
        $userProfile['wx'] = $data['wx']; // 微信
        $userProfile['parent_name'] = $data['parent_name']; // 家长姓名
        $userProfile['parent_mobile'] = $data['parent_mobile']; // 家长电话
        $userProfile['examination_score'] = $data['examination_score']; // 中考分数
        $userProfile['state'] = $data['province']; // 联系地址省份
        $userProfile['city'] = $data['city']; // 联系地址市
        $userProfile['area'] = $data['district']; // 联系地址区
        $userProfile['address_line'] = $data['address']; // 详细地址
        $profile = StudentProfile::where('user_id',$user->id)->update($userProfile);
        if($profile){
            return ['status'=>true,'message'=>'ok','data'=>[
                'user'=>User::find($user->id),
                'profile'=>$profile
            ]];
        }else {
           return ['status'=>false,'message'=>'学生档案修改失败'];
        }
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
                $msgBag->setData($reg);
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

    /**
     * 录取一个学生
     *
     * @param $id
     * @param User $manager
     * @param null $note
     * @return MessageBag
     */
    public function enrol($id,User $manager, $note = null){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR,'你无权进行此操作');
        /**
         * @var RegistrationInformatics $form
         */
        $form = null;
        if($manager->isSchoolAdminOrAbove() || $manager->isTeacher()){
            $form = RegistrationInformatics::find($id);
        }

        if($form){
            // 必须确保用户和招生简章, 是同一个学校的
            if($manager->isOperatorOrAbove() || $form->plan->school_id === $manager->getSchoolId()){
                if($form->status === RegistrationInformatics::PASSED){
                    // 该申请是被批准
                    $form->last_updated_by = $manager->id;
                    $form->note .= $note.'(录取人: '.$manager->name.')';
                    $form->status = RegistrationInformatics::APPROVED;
                    $form->approved_at = Carbon::now()->format('Y-m-d');
                    DB::beginTransaction();
                    if($form->save()){
                        // 录取完成
                        try{
                            // 如果同一个学生还有其他的报名表或者批准的报名表, 则统一置为失效的状态
                            $others = RegistrationInformatics::where('user_id',$form->user_id)
                                ->where('id','<>',$id)
                                ->whereIn('status',[RegistrationInformatics::WAITING,RegistrationInformatics::PASSED])
                                ->get();
                            if($others){
                                foreach ($others as $other) {
                                    if($other->status === RegistrationInformatics::WAITING){
                                        $otherPlanTotalCount = $other->plan->applied_count - 1;
                                        if($otherPlanTotalCount > -1){
                                            $other->plan->applied_count = $otherPlanTotalCount;
                                            $other->plan->save();
                                        }
                                    }
                                    elseif ($other->status === RegistrationInformatics::PASSED){
                                        $otherPlanTotalCount = $other->plan->passed_count - 1;
                                        if($otherPlanTotalCount > -1){
                                            $other->plan->passed_count = $otherPlanTotalCount;
                                            $other->plan->save();
                                        }
                                    }

                                    $other->status = RegistrationInformatics::USELESS;
                                    $other->note = '已被'.$form->plan->title.'录取';
                                    $other->save();
                                }
                            }

                            // 报名通过审核的人数加 1
                            $enrolCount = $form->plan->enrolled_count + 1;
                            $form->plan->enrolled_count = $enrolCount;
                            $form->plan->save();

                            DB::commit();
                            $bag->setMessage($form->name.'已经被录取');
                            $bag->setCode(JsonBuilder::CODE_SUCCESS);
                            $bag->setData($form);
                        }catch (\Exception $exception){
                            $bag->setMessage($exception->getMessage());
                            DB::rollBack();
                        }
                    }else{
                        $bag->setMessage('数据库更新操作失败: code 1');
                        DB::rollBack();
                    }
                }
                else{
                    $bag->setMessage($form->name.'已经被录取');
                }
            }
        }
        return $bag;
    }

    /**
     * 批准报名
     * @param $id
     * @param User $manager
     * @param null $note
     * @return MessageBag
     */
    public function approve($id,User $manager, $note = null){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR,'你无权进行此操作');
        /**
         * @var RegistrationInformatics $form
         */
        $form = null;
        if($manager->isSchoolAdminOrAbove() || $manager->isTeacher()){
            $form = RegistrationInformatics::find($id);
        }

        if($form){
            // 必须确保用户和招生简章, 是同一个学校的
            if($manager->isOperatorOrAbove() || $form->plan->school_id === $manager->getSchoolId()){
                if($form->status !== RegistrationInformatics::USELESS){
                    // 该申请没有被别人作废
                    $form->last_updated_by = $manager->id;
                    $form->note .= $note.'(批准人: '.$manager->name.')';
                    $form->status = RegistrationInformatics::PASSED;
                    $form->approved_at = Carbon::now()->format('Y-m-d');
                    DB::beginTransaction();
                    if($form->save()){
                        // 批准完成, 将该学生本年度的所有等待批准申请全部作废
                        try{
//                            $others = RegistrationInformatics::where('user_id',$form->user_id)
//                                ->where('id','<>',$id)
//                                ->where('status',RegistrationInformatics::WAITING)
//                                ->get();
//                            if($others){
//                                foreach ($others as $other) {
//                                    $other->status = RegistrationInformatics::USELESS;
//                                    $other->note = '已被'.$form->plan->title.'录取';
//                                    $other->save();
//                                    $otherPlanTotalCount = $other->plan->applied_count - 1;
//                                    if($otherPlanTotalCount > -1){
//                                        $other->plan->applied_count = $otherPlanTotalCount;
//                                        $other->plan->save();
//                                    }
//                                }
//                            }

                            // 报名通过审核的人数加 1
                            $passedCount = $form->plan->passed_count + 1;
                            $form->plan->passed_count = $passedCount;
                            $form->plan->save();

                            DB::commit();
                            $bag->setMessage($form->name.'的报名申请已经获得批准');
                            $bag->setCode(JsonBuilder::CODE_SUCCESS);
                            $bag->setData($form);
                        }catch (\Exception $exception){
                            $bag->setMessage($exception->getMessage());
                            DB::rollBack();
                        }
                    }else{
                        $bag->setMessage('数据库更新操作失败: code 1');
                        DB::rollBack();
                    }
                }
                else{
                    $bag->setMessage($form->name.'的报名申请已经作废');
                }
            }
        }
        return $bag;
    }

    /**
     * 拒绝录取
     * @param $id
     * @param User $manager
     * @param null $note
     * @return MessageBag
     */
    public function reject($id, User $manager, $note = null){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR,'你无权进行此操作');
        /**
         * @var RegistrationInformatics $form
         */
        $form = null;
        if($manager->isSchoolAdminOrAbove() || $manager->isTeacher()){
            $form = RegistrationInformatics::find($id);
        }

        if($form){
            if($manager->isOperatorOrAbove() || $form->plan->school_id === $manager->getSchoolId()){
                if($form->status === RegistrationInformatics::PASSED){
                    // 该申请是被批准的
                    $form->last_updated_by = $manager->id;
                    $form->note .= $note.'(拒绝人: '.$manager->name.')';
                    $form->status = RegistrationInformatics::REJECTED;
                    if($form->save()){
                        // 拒绝完成
                        $bag->setMessage($form->name.'的报名申请已经被拒绝');
                        $bag->setCode(JsonBuilder::CODE_SUCCESS);
                    }else{
                        $bag->setMessage('数据库更新操作失败: code 1');
                    }
                }
            }
        }
        return $bag;
    }

    /**
     * 拒绝报名
     * @param $id
     * @param User $manager
     * @param null $note
     * @return MessageBag
     */
    public function refuse($id, User $manager, $note = null){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR,'你无权进行此操作');
        /**
         * @var RegistrationInformatics $form
         */
        $form = null;
        if($manager->isSchoolAdminOrAbove() || $manager->isTeacher()){
            $form = RegistrationInformatics::find($id);
        }

        if($form){
            if($manager->isOperatorOrAbove() || $form->plan->school_id === $manager->getSchoolId()){
                if($form->status === RegistrationInformatics::PASSED){
                    $bag->setMessage($form->name.'的报名申请已经被'.($form->lastOperator->name??null).'批准');
                }
                else{
                    // 该申请没有被别人作废
                    $form->last_updated_by = $manager->id;
                    $form->note .= $note.'(批准人: '.$manager->name.')';
                    $form->status = RegistrationInformatics::REFUSED;
                    if($form->save()){
                        // 拒绝完成
                        // 报名通过审核的人数加 1
                        $appliedCount = $form->plan->applied_count - 1;
                        if($appliedCount >= 0){
                            $form->plan->applied_count = $appliedCount;
                            $form->plan->save();
                        }

                        $bag->setMessage($form->name.'的报名申请已经被拒绝');
                        $bag->setCode(JsonBuilder::CODE_SUCCESS);
                        $bag->setData($form);
                    }else{
                        $bag->setMessage('数据库更新操作失败: code 1');
                    }
                }
            }
        }
        return $bag;
    }

    /**
     * Func 管理员操作学生分班
     * @param 否 $data ['planId'] 招生id
     * @param 是 $data ['formId'] 学生申请id
     * @param 是 $data ['classId'] 要加入的班级id
     * @param 是 $data ['note'] 申请加入的描述
     * @param 是 $manager 当前操作的人
     * @return array
     */
    public function joinClass($data,$manager)
    {
        $bag = new MessageBag(JsonBuilder::CODE_ERROR,'你无权进行此操作');

        // 参数为空
        if (empty($data['formId']) || empty($data['classId']) || empty($manager)) {
            return $bag->setMessage('参数错误');
        }

        // 获取数据信息
        $dataInfo = null;
        if ($manager->isSchoolAdminOrAbove() || $manager->isTeacher()) {
            $dataInfo = RegistrationInformatics::find($data['formId']);
        }
        if (empty($dataInfo)) {
            return $bag->setMessage('数据不存在');
        }

        // 获取班级信息
        $gradeObj = new GradeDao();
        $gradeInfo = $gradeObj->getGradeById($data['classId']);
        if (empty($gradeInfo)) {
            return $bag->setMessage('班级信息不存在');
        }

        // 必须确保用户和招生简章, 是同一个学校的
        if($manager->isOperatorOrAbove() || $dataInfo->plan->school_id === $manager->getSchoolId()){
            // 该申请是被批准
            $dataInfo->last_updated_by = $manager->id;
            $dataInfo->note .= $data['note'].'(批准人: '.$manager->name.')';
            $dataInfo->branchclass_at = Carbon::now()->format('Y-m-d H:i:s');
            DB::beginTransaction();
            if($dataInfo->save()){
                try{
                    $gradeUserInfo = GradeUser::where('user_id',$dataInfo['user_id'])->orderBy('id','desc')->first();
                    if(!empty($gradeUserInfo)){
                        $saveData['user_id'] = $dataInfo->user_id; // 学生id
                        $saveData['name'] = $dataInfo->name; // 姓名
                        $saveData['user_type'] = 1; // 学生
                        $saveData['grade_id'] = $gradeInfo->id; // 班级id
                        $saveData['major_id'] = $gradeInfo->major->id; // 专业id
                        $saveData['department_id'] = $gradeInfo->major->department->id; // 系
                        $saveData['institute_id'] = $gradeInfo->major->institute->id; // 学院
                        $saveData['campus_id'] = $gradeInfo->major->campus->id; // 校区ID
                        $saveData['school_id'] = $gradeInfo->major->school->id; // 学校id
                        $saveData['last_updated_by'] = $manager->id; // 最后更新的用户id
                        $saveData['updated_at'] = Carbon::now()->format('Y-m-d H:i:s'); // 更新时间
                        GradeUser::where('id',$gradeUserInfo['id'])->update($saveData);
                    } else{
                        $addData['user_id'] = $dataInfo->user_id; // 学生id
                        $addData['name'] = $dataInfo->name; // 姓名
                        $addData['user_type'] = 1; // 学生
                        $addData['grade_id'] = $gradeInfo->id; // 班级id
                        $addData['major_id'] = $gradeInfo->major->id; // 专业id
                        $addData['department_id'] = $gradeInfo->major->department->id; // 系
                        $addData['institute_id'] = $gradeInfo->major->institute->id; // 学院
                        $addData['campus_id'] = $gradeInfo->major->campus->id; // 校区ID
                        $addData['school_id'] = $gradeInfo->major->school->id; // 学校id
                        $addData['last_updated_by'] = $manager->id; // 最后更新的用户id
                        $addData['created_at'] = Carbon::now()->format('Y-m-d H:i:s'); // 添加时间
                        GradeUser::insert($addData);
                    }
                    DB::commit();
                    $bag->setMessage('操作成功');
                    $bag->setCode(1000);
                    $bag->setData([]);
                }catch (\Exception $exception){
                    $bag->setMessage($exception->getMessage());
                    DB::rollBack();
                }
            }else{
                $bag->setMessage('操作失败,请稍后重试');
                DB::rollBack();
            }
        }
        return $bag;
    }


    /**
     * 获取所有 已录取 未分配班级的人
     * @param $schoolId
     * @return
     */
    public function unassigned($schoolId)
    {
        $where = ['school_id' => $schoolId, 'status'=> RegistrationInformatics::APPROVED];
        return RegistrationInformatics::where($where)->get();
    }

     /**
     * @param $id
     * @param User $manager
     * @return MessageBag
     */
    public function delete($id, User $manager){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR, '数据库错误');

        if($manager->isTeacher() || $manager->isSchoolAdminOrAbove()){
            $form = $this->getById($id);
            if($form){
                $form->last_updated_by = $manager->id;
                DB::beginTransaction();
                try{
                    if($form->save()){
                        // 检查一下, 如果这个报名表不是已经被拒绝的, 那么plan 的 applied count 人数要减一
                        if($form->status === RegistrationInformatics::WAITING){
                            $form->plan->appliedCountDecrease();
                        }
                        $form->delete();
                        $bag->setCode(JsonBuilder::CODE_SUCCESS);
                        $bag->setData($form);
                        DB::commit();
                    }else{
                        DB::rollBack();
                    }
                }catch (\Exception $exception){
                    $bag->setMessage($exception->getMessage());
                    DB::rollBack();
                }
            }else{
                $bag->setMessage('你要操作的报名表不存在');
            }
        }
        else{
            $bag->setMessage('你没有权限进行本操作');
        }
        return $bag;
    }

    /**
     * @param $id
     * @return RegistrationInformatics
     */
    public function getById($id){
        return RegistrationInformatics::find($id);
    }

    /**
     * 取消入学资格
     * @param $id
     * @param $manager
     * @return MessageBag
     */
    public function cancelEnrolment($id, $manager){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        $updated = RegistrationInformatics::where('id',$id)
            ->update(
                [
                    'status'=>RegistrationInformatics::REJECTED,
                    'last_updated_by'=>$manager->id,
                    'note'=>'已录取后, 被'.$manager->name.'取消了入学资格'
                ]
            );
        if($updated){
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
            $form = RegistrationInformatics::find($id);
            $total = $form->plan->enrolled_count - 1 ;
            if($total > -1){
                $form->plan->enrolled_count = $total;
                $form->plan->passed_count = $form->plan->passed_count - 1;
                $form->plan->applied_count = $form->plan->applied_count - 1;
                $form->plan->save();
            }
            $bag->setData($form);
        }else{
            $bag->setMessage('数据库错误');
        }
        return $bag;
    }

    /**
     * Func 获取我是否可以提交招生信息
     *
     * @param $userId 用户id
     * @param $planInfo 招生信息
     *
     * @return array
     */
    public function getRegistrationInformaticsStatusInfo($userId, $planInfo = [])
    {
        $messageArr = RegistrationInformatics::$messageArr;

        if (!intval($userId) || empty($planInfo)) return $messageArr[0];

        // 获取后台为学生是否已申请专业
        $condition[] = ['user_id', '=', $userId];
        $condition[] = ['major_id', '>', 0];
        $gradeUserCount = GradeUser::where($condition)->count();
        if ($gradeUserCount > 0) {
            return $messageArr[11];
        }

        // 招生人数已满
        if ($planInfo->enrolled_count >= $planInfo->seats) {
            return $messageArr[10];
        }

        // 如果已经报名过，并且审核中和通过，不能再次报名
        $data = $this->getInformaticsByUserIdAndPlanId($userId, $planInfo->id);
        // 状态 1待审核 2报名审核被拒绝 3报名审核已通过 4被拒绝录取 5被录取 6已报到
        if (!empty($data) && in_array($data->status, [1, 3, 5, 6])) {
            return $messageArr[$data->status];
        }
        return $messageArr[100];
    }


    /**
     * Func 获取已报名审核状态
     *
     * @param $userId 用户id
     * @param $planInfo 招生信息
     *
     * @return array
     */
    public function getRegistrationInformaticsStatusInfo1($userId, $planInfo = [])
    {
        $messageArr = RegistrationInformatics::$messageArr;

        if (!intval($userId) || empty($planInfo)) return $messageArr[0];

        return $messageArr[$planInfo->status];
    }

}
