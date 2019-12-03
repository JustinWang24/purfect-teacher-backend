<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 3:50 PM
 */

namespace App\Http\Controllers\Operator\Pipeline;
use App\Dao\Pipeline\FlowDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Pipeline\FlowRequest;

class FlowsController extends Controller
{

    public function manager(FlowRequest $request){
        $this->dataForView['pageTitle'] = '工作流程管理';
        $dao = new FlowDao();
        $this->dataForView['groupedFlows'] = $dao->getGroupedFlows($request->session()->get('school.id'));
        return view('school_manager.pipeline.flow.manager', $this->dataForView);
    }
}