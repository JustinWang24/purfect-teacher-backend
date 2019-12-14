<?php


namespace App\Http\Controllers\Teacher\Community;


use App\Dao\Forum\ForumDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ForumRequest;
use App\Utils\FlashMessageBuilder;

class DynamicController extends Controller
{
    public function index(ForumRequest $request) {
        $schoolId = $request->getSchoolId();
        $dao = new ForumDao();
        $list = $dao->getForumBySchoolId($schoolId);

        $this->dataForView['pageTitle'] = '动态列表';
        $this->dataForView['list'] = $list;
        return view('teacher.community.dynamic.index', $this->dataForView);
    }


    public function edit(ForumRequest $request) {
        $dao = new ForumDao();
        if($request->isMethod('post')) {
            $data = $request->getFormData();
            if(isset($data['is_up']) && $data['is_up'] == 'on') {
                $data['is_up'] = 1;
            } else {
                $data['is_up'] = 0;
            }
            $id = $data['id'];
            unset($data['id']);
            $result = $dao->updateForum($id, $data);
            if($result) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'编辑成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'编辑失败');
            }
            return redirect()->route('teacher.dynamic.edit',['id'=>$id]);

        }
        $forumId = $request->get('id');

        $info = $dao->find($forumId);
        $this->dataForView['pageTitle'] = '动态详情';
        $this->dataForView['forum'] = $info;
        return view('teacher.community.dynamic.edit', $this->dataForView);
    }


    public function delete() {

    }

}
