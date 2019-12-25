<?php

namespace App\Http\Controllers\Api\OA;

use App\Http\Controllers\Controller;
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
}
