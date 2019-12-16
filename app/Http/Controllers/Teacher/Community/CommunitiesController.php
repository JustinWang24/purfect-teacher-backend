<?php


namespace App\Http\Controllers\Teacher\Community;


use App\Dao\Forum\ForumCommunityDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\Forum\ForumRequest;
use App\Utils\FlashMessageBuilder;

class CommunitiesController extends Controller
{

    /**
     * @param ForumRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(ForumRequest $request) {
        $dao = new ForumCommunityDao();
        $schoolId = $request->getSchoolId();
        $list = $dao->getCommunityBySchoolId($schoolId);
        $this->dataForView['pageTitle'] = '社团列表';
        $this->dataForView['list'] = $list;
        return view('teacher.community.communities.list', $this->dataForView);
    }


    /**
     * @param ForumRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function edit(ForumRequest $request) {
        $dao = new ForumCommunityDao();

        if($request->isMethod('post')) {
            $data = $request->getCommunitiesData();
            if(empty($data['status'])) {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'状态不能为空');
                return redirect()->route('teacher.communities.edit',['id'=>$data['id']]);
            }
            $id = $data['id'];
            unset($data['id']);
            $result = $dao->updateCommunityById($id, $data);
            if($result->isSuccess()) {
                 FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'编辑成功');
            } else {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,$result->getMessage());
            }
            return redirect()->route('teacher.community.communities');

        }
        $schoolId = $request->getSchoolId();
        $communityId = $request->get('id');
        $info = $dao->getCommunity($schoolId, $communityId, false);
        $this->dataForView['pageTitle'] = '社团详情';
        $this->dataForView['communities'] = $info;

        return view('teacher.community.communities.edit', $this->dataForView);
    }


    /**
     * 删除社团
     * @param ForumRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(ForumRequest $request) {
        $communityId = $request->get('id');
        $schoolId = $request->getSchoolId();
        $dao = new ForumCommunityDao();
        $community = $dao->getCommunity($schoolId,$communityId,false);

        $result = $dao->deleteCommunity($community);
        if($result->isSuccess()) {
             FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'删除成功');
        } else {
            $msg = $result->getMessage();
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'删除失败'.$msg);
        }
        return redirect()->route('teacher.community.communities');
    }


    public function member(ForumRequest $request) {
        $communityId = $request->get('id');
        $schoolId = $request->getSchoolId();
        $dao = new ForumCommunityDao();
        $members = $dao->getCommunityMembers($schoolId, $communityId);

        $this->dataForView['pageTitle'] = '社团成员';
        $this->dataForView['members'] = $members;

        return view('teacher.community.communities.members', $this->dataForView);
    }
}
