<?php
/**
 * Created by PhpStorm.
 * User: justinwang
 * Date: 3/12/19
 * Time: 3:51 PM
 */

namespace App\Http\Requests\Pipeline;
use App\Http\Requests\MyStandardRequest;

class FlowRequest extends MyStandardRequest
{
    public function getFlowFormData(){
        return $this->get('flow', null);
    }

    public function getStartFlowData(){
        return $this->get('action',null);
    }

    /**
     * 启动一个流程时, 获取提交的流程 ID
     * @return mixed
     */
    public function getStartFlowId(){
        return $this->getStartFlowData()['flow_id'];
    }

    public function getNewFlowFirstNode(){
        return $this->get('node', null);
    }

    public function getLastNewFlow(){
        return $this->get('lastNewFlow', null);
    }
}