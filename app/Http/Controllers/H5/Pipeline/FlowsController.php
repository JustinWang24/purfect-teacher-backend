<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 12/12/19
 * Time: 6:17 PM
 */
namespace App\Http\Controllers\H5\Pipeline;

use App\Dao\Pipeline\FlowDao;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use App\BusinessLogic\Pipeline\Flow\FlowLogicFactory;

class FlowsController extends Controller
{
    /**
     * 用户启动一个流程
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|string
     */
    public function start(Request $request){
        $this->dataForView['pageTitle'] = '办事大厅';

        /**
         * @var User $user
         */
        $user = $request->user('api');

        $flowDao = new FlowDao();
        $flow = $flowDao->getById($request->get('flow_id'));

        if($user && $flow){
            $logic = FlowLogicFactory::GetInstance($user);
            $bag = $logic->open($flow);
            if($bag->isSuccess()){
                $this->dataForView['user'] = $user;
                $this->dataForView['flow'] = $flow;
                $this->dataForView['node'] = $bag->getData()['node'];
                $this->dataForView['api_token'] = $request->get('api_token');
                $this->dataForView['appName'] = 'pipeline-flow-open-app';
                return view('h5_apps.pipeline.flow_open',$this->dataForView);
            }
        }
        return '您无权使用本流程';
    }

    public function in_progress(Request $request){
        /**
         * @var User $user
         */
        $user = $request->user('api');

        $logic = FlowLogicFactory::GetInstance($user);

        if($logic){
            $flows = $logic->startedByMe();
            $this->dataForView['user'] = $user;
            $this->dataForView['flows'] = $flows;
            $this->dataForView['api_token'] = $user->api_token;
            $this->dataForView['appName'] = 'pipeline-flows-in-progress';
            return view('h5_apps.pipeline.flow_in_progress',$this->dataForView);
        }
        else{
            return '您无权使用本功能';
        }
    }
}