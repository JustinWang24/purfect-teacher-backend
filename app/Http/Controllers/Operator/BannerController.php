<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Banners\BannerDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerRequest;
use App\Models\Banner\Banner;
use App\Utils\FlashMessageBuilder;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(BannerRequest $request)
    {
        // todo :: 上传图片需要传到云盘;

        $schoolId = $request->getSchoolId();

        $data = $request->get('banner');

        $data['school_id'] = $schoolId;

        $dao = new  BannerDao;

        if (isset($data['id'])){
            $result = $dao->update($data);
        } else {
            $result = $dao->add($data);
        }

        if ($result) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'保存成功');
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'保存失败');
        }

        return redirect()->route('school_manager.banner.list');
    }


    /**
     * 添加页面展示
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add()
    {
        $posit = Banner::allPosit();
        $type = Banner::allType();
        $this->dataForView['posit'] = $posit;
        $this->dataForView['type']  = $type;
        $this->dataForView['js'][] = 'school_manager.banner.custom_js';
        return view('school_manager.banner.add', $this->dataForView);
    }


    /**
     * 修改页面展示
     * @param BannerRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BannerRequest $request)
    {

        $id = $request->get('id');

        $dao = new BannerDao;

        $data = $dao->getBannerById($id);

        $posit = Banner::allPosit();
        $type = Banner::allType();

        $this->dataForView['data']  = $data;

        $this->dataForView['posit'] = $posit;
        $this->dataForView['type']  = $type;
        $this->dataForView['js'][]  = 'school_manager.banner.custom_js';

        return view('school_manager.banner.edit', $this->dataForView);
    }

}
