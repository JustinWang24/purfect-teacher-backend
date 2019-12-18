<?php


namespace App\Http\Controllers\Api\Forum;


use App\Dao\Forum\ForumCommunityDao;
use App\Dao\Forum\ForumDao;
use App\Dao\Social\SocialDao;
use App\Dao\Students\StudentProfileDao;
use App\Dao\Users\UserDao;
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
        return JsonBuilder::Success($result->getMessage());

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
//                $data[$name] = $fileConfig['root'].DIRECTORY_SEPARATOR.$realFileName;
                $data[$name] = $realFileName;
            }else{
                Storage::delete($fileConfig['root'].DIRECTORY_SEPARATOR.$realFileName);
            }
        }
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
        $userDao = new UserDao();
        $studentDao = new StudentProfileDao();
        $result = $dao->getCommunities($schoolId);

        $data = [];
        foreach ($result as $key => $community) {
            $communityArr = $community->toArray();
            $communityArr = $dao->getPicUrl($communityArr);
            $communityArr['user_name'] = $userDao->getUserById($communityArr['user_id'])->name;
            $communityArr['user_avatar'] = asset($studentDao->getStudentInfoByUserId($communityArr['user_id'])->avatar);
            $data[$key] = $communityArr;
            $data[$key]['member'] = $community->member()->count();

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
        $userDao = new UserDao();
        $studentDao = new StudentProfileDao();
        $data = [];
        $communityArr = $dao->getCommunity($schoolId, $id)->toArray();
        $communityArr = $this->getPicUrl($communityArr);
        $communityArr['user_name'] = $userDao->getUserById($communityArr['user_id'])->name;
        $communityArr['user_avatar'] = asset($studentDao->getStudentInfoByUserId($communityArr['user_id'])->avatar);
        $data['community'] = $communityArr;

        $socialDao = new SocialDao();
        $data['socialFollow'] = $socialDao->getUserList($communityArr['user_id'],'to_user_id');
        $data['socialFollowed'] = $socialDao->getUserList($communityArr['user_id'], 'from_user_id');
        $data['like'] = $socialDao->getLike($communityArr['user_id']);
        $members = $dao->getCommunityMembers($schoolId, $id)->toArray();
        $studentDao = new StudentProfileDao();
        foreach($members as$k => $member)
        {
            $members[$k]['user_avatar']= asset($studentDao->getStudentInfoByUserId($member['user_id'])->avatar);
        }
        $data['members'] =  $members;

        $forumDao = new ForumDao();
        //公告获取
        $forumFirst = $forumDao->selectByTypeIdAndUser($communityArr['forum_type_id'],$communityArr['user_id']);
        $forumSecand = $forumDao->selectByTypeIdAndUser($communityArr['forum_type_id'],$communityArr['user_id'],'<>');
        $data['announcement'] = $forumFirst;
        $data['news'] = $forumSecand;

        return JsonBuilder::Success($data);
    }


    private  function getPicUrl($communityArr)
    {
        $fileConfig = config('filesystems.disks.community');
        $communityArr['logo'] = $fileConfig['url'].DIRECTORY_SEPARATOR.$communityArr['logo'];
        if (isset($communityArr['pic1']))
            $communityArr['pic1'] = $fileConfig['url'].DIRECTORY_SEPARATOR.$communityArr['pic1'];
        if (isset($communityArr['pic2']))
            $communityArr['pic2'] = $fileConfig['url'].DIRECTORY_SEPARATOR.$communityArr['pic2'];
        if (isset($communityArr['pic3']))
            $communityArr['pic3'] = $fileConfig['url'].DIRECTORY_SEPARATOR.$communityArr['pic3'];

        return $communityArr;
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
        $communityId = intval($request->get('community_id'));
        $reason = strip_tags($request->get('reason'));
        $name = strip_tags($request->get('name'));
        $data['school_id'] = $schoolId;
        $data['user_id'] = $user->id;
        $data['user_name'] = $user->name;
        $data['reason'] = $reason;
        $data['community_id'] = $communityId;



        if (empty($data['school_id']) || empty($data['reason']) || empty($data['community_id']))
        {
            return JsonBuilder::Error('内容不合法请重试');
        }
        $dao = new ForumCommunityDao();

        $community = $dao->getCommunity($schoolId,$communityId);
        if ($community->user_id == $user->id )
            return JsonBuilder::Error('不能加入自己的社群');

        $result = $dao->joinCommunity($data);
        return JsonBuilder::Success($result->getMessage());

    }

    /**
     * 团长查看申请列表
     * @param Request $request
     * @return string
     */
    public function joinCommunityList(Request $request)
    {
        $user = $request->user();
        $schoolId = $user->getSchoolId();
        $communityId = intval($request->get('community_id'));
        $dao = new ForumCommunityDao();
        $memberList = $dao->getCommunityMembersByStatus($schoolId,$communityId,0);
        return JsonBuilder::Success($memberList);
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
     * @return string
     */
    public function followUser(Request $request)
    {
        $user = $request->user();
        $dao = new SocialDao();
        $toUser  = intval($request->get('to_user'));
        $result  = $dao->follow($user->id, $toUser);
        if ($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }

    /**
     * 取消关注
     * @param Request $request
     * @return string
     */
    public function unFollowUser(Request $request)
    {
        $user = $request->user();
        $dao = new SocialDao();
        $toUser  = intval($request->get('to_user'));
        $result  = $dao->unfollow($user->id, $toUser);
        if ($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }

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
        if ($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
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
        if ($result) {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败');
        }
    }

}
