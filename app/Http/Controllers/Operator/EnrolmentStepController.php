<?php

namespace App\Http\Controllers\Operator;

use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Dao\EnrolmentStep\EnrolmentStepDao;

class EnrolmentStepController extends Controller
{

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
