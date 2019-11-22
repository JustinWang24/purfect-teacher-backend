<?php

namespace App\Http\Controllers\Operator;

use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Dao\EnrolmentStep\EnrolmentStepDao;

class EnrolmentStepController extends Controller
{
    /**
     * 迎新助手的后台管理页面
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manager(MyStandardRequest $request){
        $this->dataForView['pageTitle'] = '迎新助手';
        $dao = new EnrolmentStepDao();
        $this->dataForView['basics'] = $dao->getAll();

        return view('school_manager.welcome.manager', $this->dataForView);
    }

    /**
     * 创建
     * @param MyStandardRequest $request
     * @return string
     */
    public function create(MyStandardRequest $request) {
        $name = $request->get('name');
        $dao = new EnrolmentStepDao();
        $data = ['name'=>$name];
        $result = $dao->create($data);
        $msg = $result->getMessage();
        $data = $result->getData();
        if($result->isSuccess()) {
            return JsonBuilder::Success($data, $msg);
        } else {
            return JsonBuilder::Error($msg);
        }
    }
}
