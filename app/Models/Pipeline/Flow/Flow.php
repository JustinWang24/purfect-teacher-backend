<?php

namespace App\Models\Pipeline\Flow;

use App\Dao\Pipeline\ActionDao;
use App\Dao\Pipeline\FlowDao;
use App\Models\Teachers\Teacher;
use App\Models\Users\GradeUser;
use App\User;
use App\Utils\Misc\Contracts\Title;
use App\Utils\Pipeline\IFlow;
use App\Utils\Pipeline\INode;
use App\Utils\Pipeline\IUser;
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
        'school_id','name','type','icon','copy_uids','business','auto_processed'
    ];

    /**
     * 流程的所有分类
     */
    public static function Types(){
        return [
            //@TODO pipeline待删除
            IFlow::TYPE_TEACHER_ONLY=>IFlow::TYPE_TEACHER_ONLY_TXT,
            IFlow::TYPE_OFFICE=>IFlow::TYPE_OFFICE_TXT,
            IFlow::TYPE_2=>IFlow::TYPE_2_TXT,
            IFlow::TYPE_3=>IFlow::TYPE_3_TXT,
            IFlow::TYPE_4=>IFlow::TYPE_4_TXT,

            IFlow::TYPE_STUDENT_ONLY=>IFlow::TYPE_STUDENT_ONLY_TXT, // 学生专用
            IFlow::TYPE_FINANCE=>IFlow::TYPE_FINANCE_TXT, // 资助中心 学生
            IFlow::TYPE_STUDENT_COMMON=>IFlow::TYPE_STUDENT_COMMON_TXT, // 日常申请 学生


            IFlow::TYPE_1_01 => IFlow::TYPE_1_01_TXT,
            IFlow::TYPE_1_02 => IFlow::TYPE_1_02_TXT,
            IFlow::TYPE_1_03 => IFlow::TYPE_1_03_TXT,

            IFlow::TYPE_2_01 => IFlow::TYPE_2_01_TXT,
            IFlow::TYPE_2_02 => IFlow::TYPE_2_02_TXT,

            IFlow::TYPE_3_01 => IFlow::TYPE_3_01_TXT,
            IFlow::TYPE_3_02 => IFlow::TYPE_3_02_TXT,
        ];
    }

    //根据分类获取位置
    public static function getPositionByType($type) {
        $position = null;
        switch ($type) {
            case IFlow::TYPE_1_01:
            case IFlow::TYPE_1_02:
            case IFlow::TYPE_1_03:
                $position = IFlow::POSITION_1;
                break;
            case IFlow::TYPE_2_01:
            case IFlow::TYPE_2_02:
                $position = IFlow::POSITION_2;
                break;
            case IFlow::TYPE_3_01:
            case IFlow::TYPE_3_02:
                $position = IFlow::POSITION_3;
                break;
            default:
                break;
        }
        return $position;
    }
    //指定位置的分类
    public static function getTypesByPosition($position) {
        if ($position == IFlow::POSITION_1) {
            return [
                IFlow::TYPE_1_01 => IFlow::TYPE_1_01_TXT,
                IFlow::TYPE_1_02 => IFlow::TYPE_1_02_TXT,
                IFlow::TYPE_1_03 => IFlow::TYPE_1_03_TXT,
            ];
        }
        if ($position == IFlow::POSITION_2) {
            return [
                IFlow::TYPE_2_01 => IFlow::TYPE_2_01_TXT,
                IFlow::TYPE_2_02 => IFlow::TYPE_2_02_TXT,
            ];
        }
        if ($position == IFlow::POSITION_3) {
            return [
                IFlow::TYPE_3_01 => IFlow::TYPE_3_01_TXT,
                IFlow::TYPE_3_02 => IFlow::TYPE_3_02_TXT,
            ];
        }
        return [];
    }
    public static function getTitlesByType($position, $type, $roleType = 1) {
        if ($position == 2) {
            //学生端
            if ($type == 1) {
                //组织
                if ($roleType == 1) {
                    //使用者
                    return [
                        Title::ALL_TXT, Title::ORGANIZATION_EMPLOYEE, Title::ORGANIZATION_DEPUTY, Title::ORGANIZATION_LEADER
                    ];
                }else {
                    //审批者
                    return [
                        Title::ORGANIZATION_EMPLOYEE, Title::ORGANIZATION_DEPUTY, Title::ORGANIZATION_LEADER
                    ];
                }

            }else {
                //职务
                if ($roleType == 1) {
                    //使用者
                    return [
                        Title::ALL_TXT, Title::CLASS_ADVISER, Title::GRADE_ADVISER, Title::DEPARTMENT_LEADER, Title::CLASS_MONITOR, Title::CLASS_GROUP
                    ];
                }else {
                    //审批者
                    return [
                        Title::CLASS_ADVISER, Title::GRADE_ADVISER, Title::DEPARTMENT_LEADER
                    ];
                }
            }
        }else {
            //教师端
            if ($type == 1) {
                //组织
                if ($roleType == 1) {
                    //使用者
                    return [
                        Title::ALL_TXT, Title::ORGANIZATION_EMPLOYEE, Title::ORGANIZATION_DEPUTY, Title::ORGANIZATION_LEADER
                    ];
                }else {
                    //审批者
                    return [
                        Title::ORGANIZATION_EMPLOYEE, Title::ORGANIZATION_DEPUTY, Title::ORGANIZATION_LEADER
                    ];
                }
            }else {
                //职务
                if ($roleType == 1) {
                    //使用者
                    return [
                        Title::ALL_TXT, Title::CLASS_ADVISER, Title::GRADE_ADVISER, Title::DEPARTMENT_LEADER
                    ];
                }else {
                    //审批者
                    return [
                        Title::CLASS_ADVISER, Title::GRADE_ADVISER, Title::DEPARTMENT_LEADER
                    ];
                }
            }
        }
    }

    public static function business($businessid = null) {
        $list = [
            IFlow::BUSINESS_ATTENDANCE_CLOCKIN,
            IFlow::BUSINESS_ATTENDANCE_MACADDRESS
        ];
        if ($businessid) {
            foreach ($list as $item) {
                if ($businessid == $item['business']) {
                    return $item;
                }
            }
        }
        return $list;
    }

    public function nodes(){
        return $this->hasMany(Node::class);
    }

    /**
     * 获取简单的流程的按顺序排列的步骤集合
     *
     * @return Collection
     */
    public function getSimpleLinkedNodes(){
        $result = ['head' => [], 'copy' => [], 'handler' => [], 'options' => []];
        $node = $this->getHeadNode();
        $result['head'] = $node;//发起人
        if ($this->copy_uids) {
            //抄送人
            $uidArr = explode(';', $this->copy_uids);
            $result['copy'] = GradeUser::whereIn('user_id', $uidArr)->select(['user_id', 'name'])->get();
        }
        //表单
        if ($node->options) {
            foreach ($node->options as $option) {
                $option = $option->toArray();
                if (!empty($option['extra'])) {
                    $option['extra'] = json_decode($option['extra'], true);
                }
                $result['options'][] = $option;
            }
        }
        while ($node->next_node > 0){
            //获取审批人
            $next = Node::where('id',$node->next_node)
                ->with('handler')
                ->with('attachments')
                ->with('options')
                ->first();
            if (!empty($next->handler->titles) || !empty($next->handler->organizations)) {
                $result['handler'][] = $next->handler;
            }
            $node = $next;
        }
        return $result;
    }

    /**
     * 获取流程的分类描述文字
     * @return string
     */
    public function getTypeText(){
        return self::Types()[$this->type];
    }

    public function getCurrentPendingAction(IUser $user): IAction
    {
        $actionDao = new ActionDao();
        return $actionDao->getByFlowAndResult(IAction::RESULT_PENDING, $this, $user);
    }

    public function setCurrentPendingNode(INode $node, IUser $user)
    {
        // TODO: Implement setCurrentPendingNode() method.
    }

    public function getHeadNode()
    {
        return Node::where('flow_id', $this->id)
            ->where('prev_node',0)
            ->with('handler')
            ->with('options')
            ->with('attachments')
            ->first();
    }

    public function getTailNode()
    {
        return Node::where('flow_id', $this->id)
            ->where('next_node',0)
            ->with('handler')
            ->with('attachments')
            ->with('options')
            ->first();
    }

    public function canBeStartBy(IUser $user): INode
    {
        $node = null;
        $dao = new FlowDao();
        if ($dao->checkPermissionByuser($this, $user, 0)){
            $node= $this->getHeadNode();
        }
        return $node;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getIconAttribute($value){
        return $value ? asset($value) : asset('assets/img/node-icon.png');
    }
}
