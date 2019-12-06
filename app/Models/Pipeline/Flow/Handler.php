<?php

namespace App\Models\Pipeline\Flow;

use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\INode;
use App\Utils\Pipeline\INodeHandler;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Misc\Contracts\Title;

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

    public function handle(INode $node): IAction
    {
        // TODO:
    }

    public function resume(INode $node): IAction
    {
        // TODO: Implement resume() method.
    }

    /**
     * 给定了一个动作的执行者, 找出对这个执行者来说, 应该通知谁
     * @param User $user:
     * @return User[]
     */
    public function getNoticeTo(User $user)
    {
        $users = [];

        if(!empty($this->user_ids)){
            // 表示本步骤指定了确定的用户, 因此使用它
            $usersId = explode(';',$this->user_ids);
            $users = User::whereIn('id',$usersId)->get();
        }
        elseif (!empty($this->notice_to)){
            $roles = explode(';',$this->notice_to);
            $schoolId = $user->getSchoolId();

            foreach ($roles as $role) {
                switch ($role){
                    case Title::SCHOOL_PRINCIPLE: // 校长

                        break;
                    default:
                        break;
                }
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
            Title::SCHOOL_PRINCIPLE,
            Title::SCHOOL_COORDINATOR,
        ];
    }
}