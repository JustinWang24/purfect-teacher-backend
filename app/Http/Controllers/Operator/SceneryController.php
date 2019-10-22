<?php


namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Dao\Schools\SchoolResourceDao;
use Illuminate\Http\Request;
use App\Utils\Files\UploadFiles;
use App\Utils\FlashMessageBuilder;

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
     * 学校风采添加
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function add(Request $request)
    {

        if ($request->post()) {
            $file   = new UploadFiles;
            $upload = $file->uploadFile(1,'storage/uploads','213123', $request->file('image'));
            if (!$upload) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::WARNING,'上传图片失败,请重新上传');
            }

            $dao = new SchoolResourceDao;
            $data = $request->post();
//            $data['path'] = $result['file']['url'];
//            $data['size'] = $result['file']['size'];
//            $data['type'] = $result['file']['type'];
            $data['school_id'] = $request->session()->get('school.id');
            $data['path'] = 'www.baidu.com';
            $data['size'] = '100kb';
            $data['format'] = 'jpg';
            $result = $dao->addSchoolResource($data);
            if ($request){
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'添加成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'添加失败');
            }
        }

       return view('school_manager.scenery.add', $this->dataForView);
    }





}
