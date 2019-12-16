<?php


namespace App\Http\Controllers\Api\Forum;


use App\Dao\Forum\ForumCommunityDao;
use App\Dao\Social\SocialDao;
use App\Dao\Students\StudentProfileDao;
use App\Http\Controllers\Controller;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CommunityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 组织认证
     * @param Request $request
     * @return string
     */
    public function approve(Request $request)
    {
        $data = $this->optPic($request);
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $detail = strip_tags($request->get('detail'));
        $name = strip_tags($request->get('name'));
        $data['school_id'] = $schoolId;
        $data['user_id'] = $user->id;
        $data['detail'] = $detail;
        $data['name'] = $name;

        if (empty($data['school_id']) || empty($data['detail']) || empty($data['name']) || empty($data['logo']))
        {
            return JsonBuilder::Error('内容不合法请重试');
        }
        $dao = new ForumCommunityDao();
        $result = $dao->createCommunity($data);
        return JsonBuilder::Success($result);

    }

    /**
     * 处理图片
     * @param $request
     */
    public function optPic($request)
    {
        $data = [];
        $fileConfig = config('filesystems.disks.community');
        $files = $request->allFiles();
        foreach ($files as $name => $file) {
            $path = date('Ymd').DIRECTORY_SEPARATOR.date('md');
            $realFileName = $file->store($path,'community');
            $ext = pathinfo($realFileName,PATHINFO_EXTENSION);
            if (in_array($ext, ['png','jpg','jpeg','bmp'])) {
                $data[$name] = $fileConfig['root'].DIRECTORY_SEPARATOR.$realFileName;
                $data[$name] = $realFileName;
            }else{
                Storage::delete($fileConfig['root'].DIRECTORY_SEPARATOR.$realFileName);
            }
        }
        dd($data);
        return $data;
    }

    /**
     * 获取社团列表，按学校获取
     * @param Request $request
     * @return string
     */
    public function getCommunities(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new ForumCommunityDao();
        $result = $dao->getCommunities($schoolId);
        $data = [];
        foreach ($result as $key => $community) {
            $data[$key] = $community->toArray();
            $data[$key]['member'] = count($community->member());

        }
        return JsonBuilder::Success($data);
    }

    /**
     * 获取某个社团的详情
     * @param Request $request
     * @param $id
     * @return string
     */
    public function getCommunity(Request $request, $id)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $dao = new ForumCommunityDao();
        $data = [];
        $data['community'] = $dao->getCommunity($schoolId, $id);
        $socialDao = new SocialDao();
        $data['socialFollow'] = $socialDao->getFollow($data['community']->user_id);
        $data['socialFollowed'] = $socialDao->getFollowed($data['community']->user_id);
        $data['like'] = $socialDao->getLike($data['community']->user_id);
        $members = $dao->getCommunityMembers($schoolId, $id)->toArray();
        $studentDao = new StudentProfileDao();
        foreach($members as$k => $member)
        {
            $members[$k]['user_avatar']= $studentDao->getStudentInfoByUserId($member['user_id'])->avatar;
        }
        $data['members'] =  $members;

        return JsonBuilder::Success($data);
    }

    /**
     * 申请加入某个社团
     * @param Request $request
     * @return string
     */
    public function joinCommunity(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $reason = strip_tags($request->get('reason'));
        $name = strip_tags($request->get('name'));
        $data['school_id'] = $schoolId;
        $data['user_id'] = $user->id;
        $data['reason'] = $reason;

        if (empty($data['school_id']) || empty($data['reason']))
        {
            return JsonBuilder::Error('内容不合法请重试');
        }
        $dao = new ForumCommunityDao();
        $result = $dao->joinCommunity($data);
        return JsonBuilder::Success($result);

    }

    /**
     * 团长接受成员加入
     * @param Request $request
     * @return string
     */
    public function acceptCommunity(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $communityId = intval($request->get('community_id'));
        $memberId = intval($request->get('member_id'));
        $dao = new ForumCommunityDao();
        if ($dao->acceptCommunity($schoolId,$communityId,$user->id,$memberId)) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }

    /**
     * 团长拒绝成员加入
     * @param Request $request
     * @return string
     */
    public function rejectCommunity(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $communityId = intval($request->get('community_id'));
        $memberId = intval($request->get('member_id'));
        $dao = new ForumCommunityDao();
        if ($dao->rejectCommunity($schoolId,$communityId,$user->id,$memberId)) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }

    /**
     * 关注用户
     * @param Request $request
     * @return \App\Utils\ReturnData\MessageBag
     */
    public function followUser(Request $request)
    {
        $user = $request->user();
        $dao = new SocialDao();
        $toUser  = intval($request->get('to_user'));
        $result  = $dao->follow($user->id, $toUser);
        return $result;
    }

    /**
     * 取消关注
     * @param Request $request
     * @return \App\Utils\ReturnData\MessageBag
     *
     */
    public function unFollowUser(Request $request)
    {
        $user = $request->user();
        $dao = new SocialDao();
        $toUser  = intval($request->get('to_user'));
        $result  = $dao->unfollow($user->id, $toUser);
        return $result;
    }

    /**
     * 点赞
     * @param Request $request
     * @return mixed
     */
    public function like(Request $request)
    {
        $user = $request->user();
        $dao = new SocialDao();
        $toUser  = intval($request->get('to_user'));
        $result  = $dao->like($user->id, $toUser);
        return $result;
    }

    /**
     * 取消点赞
     * @param Request $request
     * @return mixed
     */
    public function unlike(Request $request)
    {
        $user = $request->user();
        $dao = new SocialDao();
        $toUser  = intval($request->get('to_user'));
        $result  = $dao->unlike($user->id, $toUser);
        return $result;
    }

}
