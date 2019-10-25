<?php


namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolResourceDao;
use App\Http\Requests\Scenery\SceneryRequest;
use App\Utils\Files\UploadFiles;
use App\Utils\FlashMessageBuilder;
use App\Models\Schools\SchoolResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SceneryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 学校风采管理
     * @param SceneryRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(SceneryRequest $request)
    {

        $dao = new SchoolResourceDao;
        $list = $dao->getPagingSchoolResourceBySchoolId(1);

        $this->dataForView['data']      =  $list;
        $this->dataForView['pageTitle'] = '学校风采管理';
        return view('school_manager.scenery.list', $this->dataForView);
    }


    /**
     * 学校风采添加表单
     * @param SceneryRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(SceneryRequest $request)
    {
       return view('school_manager.scenery.add', $this->dataForView);
    }


    /**
     * 学校风采编辑表单
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(SceneryRequest $request)
    {
        $dao = new SchoolResourceDao;

        $data = $dao->getOneSchoolResourceById((Int)$request->get('id'));

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
     * @param SceneryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(SceneryRequest $request)
    {

        $sceneryData = $request->post('scenery');
//      $sceneryData['school_id'] = $request->getSchoolId();
        $sceneryData['school_id'] = 1;

        // TODO :: 以下 上传文件代码 需要抽离成接口类

        $file = $request->file();

        $upload     = new UploadFiles;

        if (!empty($file)) {

            foreach ($file as $value) {
                $ext      = $value->getClientOriginalExtension();
                $filename = md5(rand(0, 99)) . '.' . $ext;
                $path   = $value->move('storage', $filename);
                $uploadFile[] = $upload->uploadFile('1.0','e3a4ecd9-7203-4d2f-b97a-0091a06929f0','19bab179-4164-403e-9043-0e53318acd0a', $path);
            }

            foreach ($uploadFile as $key => $val) {

                if ($val['code'] != 1000) {
                    FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'上传失败, 请重新上传');
                } else {
                    unlink('storage/'. $val['data']['file']['name']);
                }
            }

            if ($request->post('type') == SchoolResource::TYPE_IMAGE) {
                $sceneryData['path'] = $uploadFile[0]['data']['file']['url'];
                $sceneryData['size'] = $uploadFile[0]['data']['file']['size'];
            } else {
                $sceneryData['path']       = $uploadFile[0]['data']['file']['url'];
                $sceneryData['video_path'] = $uploadFile[1]['data']['file']['url'];
                $sceneryData['size']       = $uploadFile[1]['data']['file']['size'];
                $sceneryData['format']     = $ext;
            }
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
