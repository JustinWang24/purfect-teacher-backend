<?php

namespace App\Models\Pipeline\Flow;

use App\Dao\Pipeline\ActionDao;
use App\User;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Utils\Pipeline\IAction;

class Flow extends Model implements IFlow
{
    use SoftDeletes;
    public $table = 'pipeline_flows';
    public $timestamps = false;
    protected $fillable = [
        'school_id','name','type','icon'
    ];

    public function getIconUrl(){
        return $this->icon ?? asset('assets/img/node-icon.png');
    }

    /**
     * 流程的所有分类
     */
    public static function Types(){
        return [
            IFlow::TYPE_1=>IFlow::TYPE_1_TXT,
            IFlow::TYPE_2=>IFlow::TYPE_2_TXT,
            IFlow::TYPE_3=>IFlow::TYPE_3_TXT,
            IFlow::TYPE_4=>IFlow::TYPE_4_TXT,
        ];
    }

    /**
     * 获取简单的流程的按顺序排列的步骤集合
     *
     * @return Collection
     */
    public function getSimpleLinkedNodes(){
        $collection = new Collection();
        $node = $this->getHeadNode();
        $collection->add($node);
        while ($node->next_node > 0){
            $next = Node::where('id',$node->next_node)->with('handler')->first();
            $collection->add($next);
            $node = $next;
        }
        return $collection;
    }

    /**
     * 获取流程的分类描述文字
     * @return string
     */
    public function getTypeText(){
        return self::Types()[$this->type];
    }

    public function getCurrentPendingAction(User $user): IAction
    {
        $actionDao = new ActionDao();
        return $actionDao->getByFlowAndResult(IAction::RESULT_PENDING, $this, $user);
    }

    public function setCurrentPendingNode(INode $node, User $user)
    {
        // TODO: Implement setCurrentPendingNode() method.
    }

    public function getHeadNode()
    {
        return Node::where('flow_id', $this->id)->where('prev_node',0)->with('handler')->first();
    }

    public function getTailNode()
    {
        return Node::where('flow_id', $this->id)->where('next_node',0)->with('handler')->first();
    }
}
