<?php

namespace App\Http\Controllers\Api\OA;

use App\Dao\OA\HelperPageDao;
use App\Dao\OA\HelperPageTypeDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Http\Requests\OA\PartyMemberRequest;
use App\Utils\JsonBuilder;

class IndexController extends Controller
{

    // todo :: 党员接口
    /**
     * 党员活动
     * @param PartyMemberRequest $request
     * @return string
     */
    public function activity(PartyMemberRequest $request)
    {
        return JsonBuilder::Success();
    }

    /**
     * 党员学习
     * @param PartyMemberRequest $request
     * @return string
     */
    public function study(PartyMemberRequest $request)
    {
        return JsonBuilder::Success();
    }

    /***
     * 党员风采
     * @param PartyMemberRequest $request
     * @return string
     */
    public function zone(PartyMemberRequest $request)
    {
        return JsonBuilder::Success();
    }


    /**
     * 教师端 助手页
     * @param MyStandardRequest $request
     * @return string
     */
    public function helperPage(MyStandardRequest $request)
    {
        $user = $request->user();
        $dao = new HelperPageTypeDao;

        $communal = $dao->getCommunalHelperPageByUser($user);
        foreach ($communal as $key => $value) {
                $value->helperPage;
        }

        $own = $dao->getHelperPageByUser($user);
        foreach ($own as $key => $val) {
                $val->helperPage;
        }

        $data = array_merge($communal->toArray(), $own->toArray());
        return JsonBuilder::Success($data);
    }


}
