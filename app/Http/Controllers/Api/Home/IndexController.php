<?php

namespace App\Http\Controllers\Api\Home;

use App\Dao\Banners\BannerDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerRequest;
use App\Http\Requests\Home\HomeRequest;
use App\Dao\Schools\NewsDao;
use App\Utils\JsonBuilder;

class IndexController extends Controller
{

    /**
     * APP首页
     * @param HomeRequest $request
     */
    public function index(HomeRequest $request)
    {
        $school = $request->getAppSchool();

        $page   = $request->get('page');

        $dao = new NewsDao;

        $data = $dao->getNewBySchoolId($school->id, $page);
        foreach ($data as $key => $val ) {
            $news = $val->sections;
            foreach ($news as $new) {
                $news[$key] = $new->media->url;
            }
        }

        $data['school_name'] = $school->name;
        $data['school_logo'] = $school->logo;
        // todo :: 新闻动态表结构 可能字段缺少
    }


    /**
     * banner
     * @param BannerRequest $request
     * @return string
     */
    public function banner(BannerRequest $request)
    {
        $posit = $request->get('posit');
        $publicOnly = $request->has('public') && intval($request->get('public',0)) === 1;
        $dao = new BannerDao;
        $data = $dao->getBannerBySchoolIdAndPosit($request->user()->getSchoolId(), $posit, $publicOnly);
        return JsonBuilder::Success($data);
    }
}
