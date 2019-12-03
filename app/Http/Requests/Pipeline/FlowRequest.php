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
        return $this->get('flow');
    }

    public function getNewFlowFirstNode(){
        return $this->get('node');
    }

    public function getLastNewFlow(){
        return $this->get('lastNewFlow', null);
    }
}