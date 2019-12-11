<?php

namespace App\Http\Controllers\Api\Home;

use App\Dao\Banners\BannerDao;
use App\Dao\Calendar\CalendarDao;
use App\Dao\Schools\SchoolDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerRequest;
use App\Http\Requests\Home\HomeRequest;
use App\Dao\Schools\NewsDao;
use App\Http\Requests\MyStandardRequest;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;

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

    /**
     * 获取校历的接口
     * 可以提交学校的 id 或者名称进行查询. 如果未提供, 则以当前登陆用户的信息为准, 获取该用户所在学校的校历信息
     * 根据当前登陆用户, 当前的调用时间, 获取当前学期校历的接口
     *
     * @param MyStandardRequest $request
     * @return string
     */
    public function calendar(MyStandardRequest $request){
        $schoolIdOrName = $request->get('school', null);
        $dao = new SchoolDao();
        if($schoolIdOrName){
            $school = $dao->getSchoolById($schoolIdOrName);
            if(!$school){
                $school = $dao->getSchoolByName($schoolIdOrName);
            }
        }
        else{
            $school = $dao->getSchoolById($request->user()->getSchoolId());
        }

        if(!$school){
            return JsonBuilder::Error('找不到学校的信息');
        }
        else{
            $dao = new CalendarDao();
            return JsonBuilder::Success([
                'calendar'=>$dao->getCalendar($school->configuration)
            ]);
        }
    }
}
