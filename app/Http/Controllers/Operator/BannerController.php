<?php

namespace App\Http\Controllers\Operator;

use App\Dao\Banners\BannerDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Banner\BannerRequest;
use App\Models\Banner\Banner;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $param['app'] = $request->input('app', '');
        $param['status'] = $request->input('status', '');
        $this->dataForView['pageTitle'] = '资源位管理';
        $dao = new BannerDao;
        $this->dataForView['data'] = $dao->getBannerBySchoolId($schoolId,$param);

        $this->dataForView['redactor'] = true;
        $this->dataForView['redactorWithVueJs'] = true;
        $this->dataForView['js'] = [
            'school_manager.recruitStudent.consult.note_js'
        ];

        return view('school_manager.banner.index', $this->dataForView);
    }

    /**
     * Func 获取分类
     * @param Request $request
     * @return string
     */
    public function get_type(BannerRequest $request)
    {
      $dao = new BannerDao;
      $infos = $dao->getBannerTypeListInfo();
      return JsonBuilder::Success($infos, '操作成功');
    }


    /**
     * Func 获取轮播图数据
     * @param Request $request
     * @return string
     */
    public function get_banner_one(BannerRequest $request)
    {
      $id = (Int)$request->input('id', '');
      $infos = [];
      if ($id) {
        $dao = new BannerDao;
        $infos = $dao->getBannerById($id);
      }
      return JsonBuilder::Success($infos, '获取数据');
    }

    /**
     * 保持数据
     * @param BannerRequest $request
     * @return string
     */
    public function save_banner(BannerRequest $request)
    {
      $id = (Int)$request->input('id', ''); // id
      $school_id = (Int)$request->input('school_id', ''); // 学校id
      $image_url = (String)$request->input('image_url', ''); // 图片
      $title = (String)$request->input('title', ''); // 标题
      $external = (String)$request->input('external', ''); // 外部字段
      $content = (String)$request->input('content', ''); // 内容
      $app = (Int)$request->input('app', ''); // 终端
      $posit = (Int)$request->input('posit', ''); // 位置
      $type = (Int)$request->input('type', ''); // 类型
      $public = (Int)$request->input('public', 1); // 是否需要登录才可看到
      $status = (Int)$request->input('status',1); // 状态

      $image_url = parse_url($image_url)['path'];

      // 验证字段值
      if (!$image_url) {
        return JsonBuilder::Error('请上传图片');
      }
      if (!$title) {
        return JsonBuilder::Error('请填写标题');
      }
      if (!$type) {
        return JsonBuilder::Error('请选择类型');
      }

      // 添加或更新数据
      $saveData['id'] = $id;
      $saveData['school_id'] = $school_id;
      $saveData['image_url'] = $image_url;
      $saveData['title'] = $title;
      $saveData['external'] = in_array($type,[3,8,13])?$external:"";
      $saveData['content'] = in_array($type,[2,12])?$content:"";
      $saveData['app'] = $app;
      $saveData['posit'] = $posit;
      $saveData['type'] = $type;
      $saveData['public'] = $public;
      $saveData['status'] = $status;

      $dao = new  BannerDao;
      if ($id) {
        $result = $dao->update($saveData);
      } else {
        unset($saveData['id']);
        $result = $dao->add($saveData);
      }
      if($result) {
        return JsonBuilder::Success('操作成功');
      } else {
        return JsonBuilder::Error('操作失败,请稍后重试');
      }
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

    /**
     * Func 排序
     * @param $request
     * @return view
     */
    public function top_banner_sort(BannerRequest $request){
      $id = (Int)$request->get('id', 0);
      $sort = (Int)$request->get('sort', 1000);
      if ($id && $sort) {
        $data['id'] = $id;
        $data['sort'] = $sort;
        (new BannerDao())->update($data);
      }
    }
}
