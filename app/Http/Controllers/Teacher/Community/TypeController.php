<?php

namespace App\Http\Controllers\Teacher\Community;

use App\Dao\Forum\ForumDao;
use App\Dao\Forum\ForumTypeDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ForumRequest;
use App\Models\Forum\ForumType;
use App\Utils\FlashMessageBuilder;
use Illuminate\Contracts\View\Factory;
use Illuminate\View\View;

class TypeController extends Controller
{

    /**
     * 论坛 社团 类型列表
     * @param ForumRequest $request
     * @return Factory|View
     */
    public function index(ForumRequest $request)
    {
        $dao = new ForumTypeDao;
        $list = $dao->getAllType();

        foreach ($list as $val) {
            /**
             * @var  $val ForumType
             */
            $val->school;
        }
        $this->dataForView['pageTitle'] = '分类列表';
        $this->dataForView['list'] = $list;

        return view('teacher.community.type.index', $this->dataForView);
    }

    /**
     * 添加页面
     * @return Factory|View
     */
    public function add()
    {
        $this->dataForView['pageTitle'] = '分类列表';
        $this->dataForView['data'] = ForumType::type();
        return view('teacher.community.type.add', $this->dataForView);
    }


    /**
     * 添加 分类
     * @param ForumRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save(ForumRequest $request)
    {
        $data   = $request->get('data');
        $schoolId = $request->getSchoolId();
        $dao = new ForumTypeDao;
        $data['school_id'] = $schoolId;
        $result = $dao->add($data);
        if($result) {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'添加成功');
        } else {
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'添加失败');
        }
        return redirect()->route('teacher.community.dynamic.type');
    }

}
