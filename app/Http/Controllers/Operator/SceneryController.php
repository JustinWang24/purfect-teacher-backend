<?php


namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolResourceDao;
use Illuminate\Http\Request;
use App\Utils\Files\UploadFiles;
use App\Utils\FlashMessageBuilder;
use App\Models\Schools\SchoolResource;

class SceneryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 学校风采管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request)
    {
        $dao = new SchoolResourceDao;
        $list = $dao->getPagingSchoolResourceBySchoolId($request->session()->get('school.id'));

        $this->dataForView['data']      =  $list;
        $this->dataForView['pageTitle'] = '学校风采管理';
        return view('school_manager.scenery.list', $this->dataForView);
    }


    /**
     * 学校风采添加表单
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request)
    {
       return view('school_manager.scenery.add', $this->dataForView);
    }


    /**
     * 学校风采编辑表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request)
    {
        $dao = new SchoolResourceDao;
        $data = $dao->getSchoolResourceBySchoolIdOrUuid((Int) $request->get('id'));

        if(empty($data->toArray())) {

            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'未找到数据');
            return redirect()->route('school_manager.scenery.list');

        } else {

            $this->dataForView['data'] = $data;
            return view('school_manager.scenery.edit', $this->dataForView);

        }
    }

    /**
     * 学校风采修改|插入
     */
    public function save(Request $request)
    {
        $sceneryData = $request->post('scenery');
        $sceneryData['school_id'] = $request->session()->get('school.id');

        if($sceneryData['type'] == SchoolResource::TYPE_IMAGE) {
            $image = $request->file();
            //  TODO :: 图片上传到静态服务器
            $upload = ['file' => ['url' => 'www.baid.com', 'type' => 'jpg', 'size' => '1000000']];
            $sceneryData['path'] = $upload['file']['url'];
            $sceneryData['format'] = $upload['file']['type'];
            $sceneryData['size'] = $upload['file']['size'];
        } else {
            // 视频
        }

        $dao = new SchoolResourceDao();
        if (isset($sceneryData['id'])) {
            $result = $dao->updateSchoolResource($sceneryData);
        } else {
            $result = $dao->addSchoolResource($sceneryData);
        }

        if ($result) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'保存成功');
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'保存失败');
        }

        return redirect()->route('school_manager.scenery.list');
    }
}
