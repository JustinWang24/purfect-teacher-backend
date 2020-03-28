<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 11:04 AM
 */

namespace App\Dao\Pipeline;

use App\BusinessLogic\OrganizationTitleHelpers\TitleToUsersFactory;
use App\Dao\Users\UserOrganizationDao;
use App\Models\Pipeline\Flow\Flow;
use App\Models\Pipeline\Flow\Node;
use App\Models\Pipeline\Flow\UserFlow;
use App\Models\Teachers\Teacher;
use App\Models\Users\UserOrganization;
use App\User;
use App\Utils\JsonBuilder;
use App\Utils\Misc\Contracts\Title;
use App\Utils\Pipeline\IAction;
use App\Utils\ReturnData\IMessageBag;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

class FlowDao
{
    /**
     * 获取分类的流程集合
     * @param $schoolId
     * @param array $types
     * @param boolean $forApp
     * @return array
     */
    public function getGroupedFlows($schoolId, $types = [], $forApp = false){
        $data = [];
        if(empty($types)){
            $types = array_keys(Flow::Types());
        }

        $flows = Flow::select(['id','name','icon','type'])
            ->where('school_id',$schoolId)
            ->where('closed', 0)
            ->whereIn('type',$types)
            ->orderBy('type','asc')->get();

        foreach ($types as $key){
            $data[$key] = [];
        }

        foreach ($flows as $flow) {
            if(in_array($flow->type, $types)){
                if($forApp){
                    //$flow->icon = str_replace('.png','@2x.png',$flow->icon);
                }
                $data[$flow->type][] = $flow;
            }
        }

        $groups = [];

        foreach ($data as $key=>$items) {
            $groups[] = [
                'name'=>Flow::Types()[$key] ?? '',
                'key'=>$key,
                'flows'=>$items
            ];
        }

        return $groups;
    }

    public function getListByBusiness($schoolId, $business){
        return Flow::where('school_id', $schoolId)->where('business', $business)->where('closed', 0)->get();
    }

    public function transTitlesToUser($titles, $organizations, User $user) {
        $return = [];
        $schoolId = $user->getSchoolId();
        //组织部门
        if (!empty($organizations)) {
            $organizationArr = explode(';', trim($organizations, ';'));
            $titlesArr = explode(';', trim($titles, ';'));
            $organizations = array_unique(UserOrganization::whereHas('organization', function($query) use($schoolId, $organizationArr) {
                $query->where('school_id', $schoolId)->whereIn('name', $organizationArr);
            })->pluck('organization_id')->toArray());

            //如果用户存在相同组织则仅显示自己所在组织
            $userOrganizationId = $user->organizations->whereIn('organization_id', $organizations)->pluck('organization_id')->toArray();
            if ($userOrganizationId) {
                $organizations = $userOrganizationId;
            }
            foreach ($titlesArr as $title) {
                if ($title == Title::ORGANIZATION_EMPLOYEE) {
                    $titleId = Title::ORGANIZATION_EMPLOYEE_ID;
                }
                if ($title == Title::ORGANIZATION_DEPUTY) {
                    $titleId = Title::ORGANIZATION_DEPUTY_ID;
                }
                if ($title == Title::ORGANIZATION_LEADER) {
                    $titleId = Title::ORGANIZATION_LEADER_ID;
                }
                if (isset($titleId)) {
                    $userOrganizationList = UserOrganization::with('user')->whereIn('organization_id', $organizations)->where('title_id', $titleId)->get();
                }else {
                    $userOrganizationList = UserOrganization::with('user')->whereIn('organization_id', $organizations)->get();
                }
                foreach ($userOrganizationList as $userOrganization) {
                    $return[$userOrganization->title][] = $userOrganization->user;
                }
            }

        }else {
            //职务
            $titlesArr = explode(';', trim($titles, ';'));
            foreach ($titlesArr as $title) {
                $helper = TitleToUsersFactory::GetInstance($title, $user);
                $titleUsers = $helper->getUsers();
                $return[$title] = $titleUsers;
            }
        }

        return $return;
    }

    /**
     * 检测用户是否有权限使用
     * @param Flow $flow
     * @param User $user
     * @param int $nodeId
     * @return bool
     */
    public function checkPermissionByuser(Flow $flow, User $user, $nodeId = 0) {
        $schoolId = $user->getSchoolId();
        if (empty($nodeId)) {
            $node = $flow->getHeadNode();
        }else {
            $node = Node::where('id',$nodeId)
                        ->with('handler')
                        ->with('attachments')
                        ->with('options')
                        ->first();
        }
        //验证目标群体
        $nodeSlugs = explode(';', trim($node->handler->role_slugs, ';'));
        if ($user->isStudent()) {
            $userSlug = '学生';
        }elseif ($user->isEmployee()) {
            $userSlug = '职工';
        }elseif ($user->isTeacher()) {
            $userSlug = '教师';
        }else {
            $userSlug = '';
        }
        if (!in_array($userSlug, $nodeSlugs)) {
            return false;
        }

        //验证组织
        if ($node->handler->organizations) {
            $nodeOrganizationArr = explode(';', trim($node->handler->organizations, ';'));

            $nodeTitlesArr = explode(';', trim($node->handler->titles, ';'));
            $whereIn = [];
            foreach ($nodeTitlesArr as $title) {
                if ($title == Title::ALL_TXT) {
                    $whereIn = [];
                    break;
                }
                if ($title == Title::ORGANIZATION_EMPLOYEE) {
                    $whereIn[] = Title::ORGANIZATION_EMPLOYEE_ID;
                }
                if ($title == Title::ORGANIZATION_DEPUTY) {
                    $whereIn[] = Title::ORGANIZATION_DEPUTY_ID;
                }
                if ($title == Title::ORGANIZATION_LEADER) {
                    $whereIn[] = Title::ORGANIZATION_LEADER_ID;
                }
            }

            if ($whereIn) {
                $nodeOrganizations = UserOrganization::whereHas('organization', function ($query) use($schoolId, $nodeOrganizationArr) {
                    $query->where('school_id', $schoolId)->whereIn('name', $nodeOrganizationArr);
                })->where('user_id', $user->id)->whereIn('title_id', $whereIn)->count();
            }else {
                $nodeOrganizations = UserOrganization::whereHas('organization', function ($query) use($schoolId, $nodeOrganizationArr) {
                    $query->where('school_id', $schoolId)->whereIn('name', $nodeOrganizationArr);
                })->where('user_id', $user->id)->count();
            }

            if ($nodeOrganizations < 1) {
                return false;
            }
        }else {
            //职务
            $nodeTitlesArr = explode(';', trim($node->handler->titles, ';'));
            $check = false;//满足其中之一即可
            foreach ($nodeTitlesArr as $title) {
                //全体
                if ($title == Title::ALL_TXT) {
                    $check = true;
                    break;
                }
                //班长
                if ($title == Title::CLASS_MONITOR) {
                    if ($user->monitor) {
                        $check = true;
                        break;
                    }
                }
                //团支书
                if ($title == Title::CLASS_GROUP) {
                    if ($user->group) {
                        $check = true;
                        break;
                    }
                }
                //班主任
                if ($title == Title::CLASS_ADVISER) {
                    if (Teacher::myGradeManger($user->id)) {
                        $check = true;
                        break;
                    }
                }
                //年级组长
                if ($title == Title::GRADE_ADVISER) {
                    if (Teacher::myYearManger($user->id)) {
                        $check = true;
                        break;
                    }
                }
                //系主任
                if ($title == Title::DEPARTMENT_LEADER) {
                    if (Teacher::myDepartmentManger($user->id)) {
                        $check = true;
                        break;
                    }
                }
                //副校长
                if ($title == Title::SCHOOL_DEPUTY) {
                    $userOrganizationDao = new UserOrganizationDao();
                    $deputy = $userOrganizationDao->getDeputyPrinciples($schoolId);
                    if ($deputy) {
                        foreach ($deputy as $dep) {
                            if ($dep->user_id == $user->id) {
                                $check = true;
                                break 2;
                            }
                        }
                    }
                }
                //校长
                if ($title == Title::SCHOOL_PRINCIPAL) {
                    $userOrganizationDao = new UserOrganizationDao();
                    $principle = $userOrganizationDao->getPrinciple($schoolId);
                    if ($principle && $principle->user_id == $user->id) {
                        $check = true;
                        break;
                    }
                }
                //书记
                if ($title == Title::SCHOOL_COORDINATOR) {
                    $userOrganizationDao = new UserOrganizationDao();
                    $coordinator = $userOrganizationDao->getCoordinator($schoolId);
                    if ($coordinator && $coordinator->user_id == $user->id) {
                        $check = true;
                        break;
                    }
                }
            }
            if (!$check) {
                return false;
            }
        }
        return true;
    }

    /**
     * 开始一个流程
     * @param Flow|int $flow
     * @param User|int $user
     * @return IMessageBag
     */
    public function start($flow, $user){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        $flowId = $flow;
        if(is_object($flow)){
            $flowId = $flow->id;
        }
        // 获取第一个 node
        $nodeDao = new NodeDao();
        $headNode = $nodeDao->getHeadNodeByFlow($flow);
        if($headNode){
            $actionData = [
                'flow_id'=>$flowId,
                'user_id'=>$user->id??$user,
                'node_id'=>$headNode->id,
                'result'=>IAction::RESULT_PENDING,
            ];

            DB::beginTransaction();

            try{
                $userFlow = UserFlow::create(
                    ['flow_id' => $flowId, 'user_id' => $user->id??$user]
                );
                $actionDao = new ActionDao();
                $action = $actionDao->create($actionData, $userFlow);
                $bag->setData($action);
                $bag->setCode(JsonBuilder::CODE_SUCCESS);
                DB::commit();
            }
            catch (\Exception $exception){
                DB::rollBack();
                $bag->setMessage($exception->getMessage());
            }
            return $bag;
        }

        $bag->setMessage('找不到指定的流程的第一步');
        return $bag;
    }

    /**
     * @param $id
     * @return Flow
     */
    public function getById($id){
        return Flow::find($id);
    }

    /**
     * 创建流程, 那么应该同时创建第一步, "发起" node. 也就是表示, 任意流程, 创建时会默认创建头部
     * @param $data
     * @param string $headNodeDescription
     * @param array $nodeAndHandlersDescriptor: 该流程可以由谁来发起, 如果为空数组, 表示可以由任何人发起.
     * @return IMessageBag
     */
    public function create($data, $headNodeDescription = '', $nodeAndHandlersDescriptor = []){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);

        DB::beginTransaction();

        try{
            $flow = Flow::create($data);
            $nodeDao = new NodeDao();
            // 创建流程后, 默认必须创建一个"发起"的步骤作为第一步
            $headNode = $nodeDao->insert([
                'name'=>'发起'.$flow->name.'流程',
                'description'=>$headNodeDescription,
                'attachments'=> $nodeAndHandlersDescriptor['attachments'] ?? ''
            ], $flow);

            // 创建头部流程的 handlers
            $handlerDao = new HandlerDao();
            $handlerDao->create($headNode, $nodeAndHandlersDescriptor);

            DB::commit();
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
            $bag->setData($flow);
            return $bag;
        }
        catch (\Exception $exception){
            DB::rollBack();
            $bag->setMessage($exception->getMessage());
            return $bag;
        }
    }

    /**
     * 更新流程
     * @param $data
     * @param string $headNodeDescription
     * @param array $nodeAndHandlersDescriptor
     * @param $flowId
     * @return MessageBag
     */
    public function update($data, $headNodeDescription = '', $nodeAndHandlersDescriptor = [], $flowId){
        $bag = new MessageBag(JsonBuilder::CODE_ERROR);
        $flow = Flow::where('id', $flowId)->first();
        if (!$flow) {
            $bag->setMessage('flow_id 有误');
            return $bag;
        }

        DB::beginTransaction();

        try{
            Flow::where('id', $flowId)->update($data);
            $firstNode = $flow->getHeadNode();

            // 更新头部流程的 handlers
            $handlerDao = new HandlerDao();
            $handlerDao->update($firstNode, $nodeAndHandlersDescriptor);

            DB::commit();
            $bag->setCode(JsonBuilder::CODE_SUCCESS);
            $bag->setData($flow);
            return $bag;
        }
        catch (\Exception $exception){
            DB::rollBack();
            $bag->setMessage($exception->getMessage());
            return $bag;
        }
    }

    /**
     * @param $flowId
     * @return mixed
     */
    public function delete($flowId){
        return Flow::where('id',$flowId)->update(['closed' => 1]);
    }

    /**
     * 是否能update true=能
     * @param $flowId
     * @return bool
     */
    public function canBeUpdate($flowId) {
        return UserFlow::where('flow_id', $flowId)->first() ? false : true;
    }
}
