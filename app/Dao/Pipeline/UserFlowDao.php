<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 9/12/19
 * Time: 9:06 PM
 */

namespace App\Dao\Pipeline;


use App\Dao\Students\StudentProfileDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Dao\Users\UserDao;
use App\Models\Pipeline\Flow\UserFlow;
use App\User;
use App\Utils\Pipeline\IAction;
use App\Utils\Pipeline\IUserFlow;

class UserFlowDao
{
    /**
     * @param $id
     * @return UserFlow
     */
    public function getById($id){
        return UserFlow::find($id);
    }

    /**
     * 更新
     * @param $id
     * @param $data
     * @return mixed
     */
    public function update($id, $data){
        return UserFlow::where('id',$id)->update($data);
    }

    /**
     * @param $id
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function terminate($id){
        $actions = (new ActionDao())->getHistoryByUserFlow($id, true);
        $this->update($id,['done'=>IUserFlow::TERMINATED]);
        return $actions;
    }

    /**
     * 获取一个用户申请的全部历史数据
     * @param $userFlowId
     * @return array
     */
    public function getFlowHistory($userFlowId){
        $userFlow = $this->getById($userFlowId);

        $actionDao = new ActionDao();
        $actions = $actionDao->getHistoryByUserFlow($userFlowId, true);

        $nodeDao = new NodeDao();
        $nodes = $nodeDao->getNodesByFlowId($userFlow->flow_id);

        $data = [
            'userFlow'=>$userFlow,
            'nodes'=>[]
        ];

        $nodesData = [];
        $userDao = new UserDao();

        foreach ($nodes as $key => $node) {
            $nodeActions = [];
            foreach ($actions as $act) {
                if($act->node_id === $node->id){
                    /**
                     * @var User $u
                     */
                    $u = $userDao->getUserById($act->user_id);
                    $act->personal = [
                        'name'=> $u->getName(),
                        'avatar'=> $u->profile->avatar,
                    ];
                    // 把执行动作的用户名和头像放进来
                    $nodeActions[] = $act;
                }
            }
            $node->actions = $nodeActions;
            $nodesData[] = $node;
        }
        $data['nodes'] = $nodesData;
        return $data;
    }

    public function getByFlowId($flowId){
        return UserFlow::where('flow_id', $flowId)->get();
    }
}