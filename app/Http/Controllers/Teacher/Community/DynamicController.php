<?php


namespace App\Http\Controllers\Teacher\Community;


use App\Dao\Forum\ForumDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ForumRequest;

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

}
