<?php

namespace App\Models\Pipeline\Flow;

use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\INode;
use App\Utils\Pipeline\INodeHandler;
use Illuminate\Database\Eloquent\Model;

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

    public function getNoticeTo(User $user)
    {
        
    }

    /**
     * 获取所有可以参与审核的角色
     * @return array
     */
    public static function HigherLevels(){
        return [
            INodeHandler::CLASS_ADVISER,
            INodeHandler::GRADE_ADVISER,
            INodeHandler::ORGANIZATION_DEPUTY,
            INodeHandler::ORGANIZATION_LEADER,
            INodeHandler::DEPARTMENT_LEADER,
            INodeHandler::SCHOOL_DEPUTY,
            INodeHandler::SCHOOL_PRINCIPLE,
            INodeHandler::SCHOOL_COORDINATOR,
        ];
    }
}