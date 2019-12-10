<?php

namespace App\Models\Pipeline\Flow;

use App\BusinessLogic\OrganizationTitleHelpers\TitleToUsersFactory;
use App\Dao\Pipeline\ActionDao;
use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use App\Utils\Pipeline\INodeHandler;
use App\Utils\Pipeline\IUser;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Misc\Contracts\Title;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class Handler extends Model implements INodeHandler
{
    public $timestamps = false;
    public $table = 'pipeline_handlers';
    public $fillable = [
        'node_id',
        'role_slugs','organizations','titles', // 当前步骤谁来使用
        'notice_to','user_ids', // 下一步交由谁来审核
    ];

    public function node(){
        return $this->belongsTo(Node::class,'node_id');
    }

    /**
     * 当前步骤的处理器, 接受前一个步骤的用户和动作, 然后根据当前设定的审核人, 生成所有等待执行 pending 的动作记录
     * @param IUser $prevActionUser
     * @param IAction $prevAction
     * @param INode $nextNode
     * @return bool
     */
    public function handle(IUser $prevActionUser, IAction $prevAction, $nextNode)
    {
        /**
         * 根据传入的用户, 就是刚刚完成一个步骤的用户
         */
        $users = $this->getNoticeTo($prevActionUser);
        $dao = new ActionDao();
        foreach ($users as $u) {
            /**
             * @var User $u
             */
            $data = [
                'flow_id'=>$prevAction->flow_id,
                'node_id'=>$nextNode->id,
                'user_id'=>$u->id,
                'result'=>IAction::RESULT_PENDING,
            ];
            $dao->create($data, $prevAction->getTransactionId());
        }
        return true;
    }

    public function reject(IUser $user, IFlow $flow)
    {
        // TODO: Implement resume() method.

        return true;
    }

    /**
     * 给定了一个动作的执行者, 找出对这个执行者来说, 应该通知谁
     * @param IUser $user:
     * @return User[]
     */
    public function getNoticeTo(IUser $user)
    {
        $users = [];

        if(!empty($this->user_ids)){
            // 表示本步骤指定了确定的用户, 因此使用它
            $usersIds = $this->user_ids;
            if(Str::endsWith($this->user_ids,';')){
                $usersIds = substr($this->user_ids, 0, strlen($this->user_ids) - 1);
            }
            $usersId = explode(';',$usersIds);
            $users = User::whereIn('id',$usersId)->get();
        }
        elseif (!empty($this->notice_to)){
            $noticeTo = $this->notice_to;
            if(Str::endsWith($this->notice_to,';')){
                $noticeTo = substr($this->notice_to, 0, strlen($this->notice_to) - 1);
            }
            $roles = explode(';',$noticeTo);
            foreach ($roles as $role) {
                $helper = TitleToUsersFactory::GetInstance($role, $user);
                $titleUsers = $helper->getUsers();
                $users = array_merge($users, $titleUsers);
            }
        }
        return $users;
    }

    /**
     * 获取所有可以参与审核的角色
     * @return array
     */
    public static function HigherLevels(){
        return [
            Title::CLASS_ADVISER,
            Title::GRADE_ADVISER,
            Title::ORGANIZATION_DEPUTY,
            Title::ORGANIZATION_LEADER,
            Title::DEPARTMENT_LEADER,
            Title::SCHOOL_DEPUTY,
            Title::SCHOOL_PRINCIPAL,
            Title::SCHOOL_COORDINATOR,
        ];
    }
}