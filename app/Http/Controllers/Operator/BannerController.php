<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Banners\BannerDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerRequest;
use App\Models\Banner\Banner;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;

class BannerController extends Controller
{

    /**
     * banner 列表
     * @param BannerRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(BannerRequest $request)
    {
        $schoolId = $request->getSchoolId();
        $this->dataForView['pageTitle'] = '资源位管理';
        $dao = new BannerDao;
        $this->dataForView['data'] = $dao->getBannerBySchoolId($schoolId);
        return view('school_manager.banner.index', $this->dataForView);
    }

    /**
     * 保持数据
     * @param BannerRequest $request
     * @return string
     */
    public function save(BannerRequest $request)
    {
        $schoolId = $request->getSchoolId();

        $data = $request->get('banner');

        $data['school_id'] = $schoolId;

        $dao = new  BannerDao;

        if (isset($data['id'])){
            $result = $dao->update($data);
        } else {
            $result = $dao->add($data);
        }

        return $result ? JsonBuilder::Success() : JsonBuilder::Error('保存失败');
    }

    /**
     * @param BannerRequest $request
     * @return string
     */
    public function load(BannerRequest $request){
        $banner = (new BannerDao())->getBannerById($request->get('id'));
        return $banner ? JsonBuilder::Success(['banner'=>$banner]) : JsonBuilder::Error('找不到指定的资源位');
    }

    /**
     * @param BannerRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(BannerRequest $request){
        $id = $request->get('id');
        $dao = new BannerDao;
        if($dao->delete($id)){
            FlashMessageBuilder::Push($request,'success','删除成功');
        }
        else{
            FlashMessageBuilder::Push($request,'danger','删除失败');
        }
        return redirect()->route('school_manager.banner.list');
    }
}
