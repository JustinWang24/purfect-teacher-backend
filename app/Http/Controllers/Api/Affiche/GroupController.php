<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/31
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Affiche;

use App\Dao\Affiche\Api\PraiseDao;
use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Affiche\GroupRequest;

use App\Dao\Users\UserDao;
use App\Dao\Affiche\Api\UserFollowDao;
use App\Dao\Affiche\Api\CollegeGroupDao;
use App\Dao\Affiche\Api\CollegeGroupPicsDao;
use App\Dao\Affiche\Api\CollegeGroupJoinDao;
use App\Dao\Affiche\Api\CollegeGroupNoticesDao;

use App\Models\Affiche\Affiche;
use App\Models\Affiche\CollegeGroup;
use App\Models\Affiche\CollegeGroupJoin;
use App\Models\Notices\AppProposalImage;
use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;

class GroupController extends Controller
{
    /**
     * Func 添加群
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_typeid']  是   类型(1:学生会,2:社团)
     * @param['group_pics']  是   图片
     * @param['group_title']  是   名称
     * @param['group_content']  是   简介
     *
     * @return Json
     */
    public function add_group_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_typeid = (Int)$request->input('group_typeid', 0);
        $group_title = (String)$request->input('group_title', '');
        $group_content = (String)$request->input('group_content', '');
        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!in_array($group_typeid, [1, 2])) {
            return JsonBuilder::Error('类型值错误');
        }
        if (!trim($group_title)) {
            return JsonBuilder::Error('名称不能为空');
        }
        if (!trim($group_content)) {
            return JsonBuilder::Error('简介不能为空');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        // 获取我之前是否有待审核信息
        $collegeGroupObj = new CollegeGroupDao();
        $data = $collegeGroupObj->getCheckGroupOneInfo($user_id, $group_typeid);
        if (!empty($data) && $data['status'] == -1) {
            return JsonBuilder::Error('您的【' . $data['group_title'] . '】还在审核中,不能提交');
        }

        // 查询数据是否存在
        $data = $collegeGroupObj->getCollegeGroupNameOneInfo($user_id, $group_typeid, $group_title);
        if (!empty($data)) {
            return JsonBuilder::Error('数据已存在');
        }

        // 上传图片
        $images = $request->file('group_pics');
        if (empty($images)) {
            return JsonBuilder::Error('请上传图片');
        }
        // logo图片地址
        $group_pics = AppProposalImage::proposalUploadPathToUrl(
            $images->store(AppProposalImage::DEFAULT_UPLOAD_PATH_PREFIX)
        );

        // 组装数据
        $addData['user_id'] = $user_id;
        $addData['school_id'] = $school_id;
        $addData['campus_id'] = $campus_id;
        $addData['group_typeid'] = (Int)$group_typeid;
        $addData['group_title'] = (String)trim($group_title);
        $addData['group_content'] = (String)trim($group_content);
        $addData['group_pics'] = (String)$group_pics;
        if ($groupid = $collegeGroupObj->addCollegegroupInfo($addData))
        {
            // 添加管理员
            $addData1['status'] = 1;
            $addData1['group_id'] = $groupid;
            $addData1['user_id'] = $user_id;
            $addData1['school_id'] = $school_id;
            $addData1['campus_id'] = $campus_id;
            $addData1['join_typeid'] = 1; // 角色(1:管理员,2:会长,3:秘书长,4:普通用户)
            $addData1['join_userid'] = 0; // 是否查看(1:未查看,0:已查看)
            $addData1['join_adminid'] = 0; // 管理员是否读取(1:未查看,0:已查看)
            $collegeGroupJoinObj = new CollegeGroupJoinDao();
            $collegeGroupJoinObj->addCollegeGroupJoinInfo($addData1);

            // 重新计算人数
            $collegeGroupObj->calculateCollegeGroupInfo($groupid);

            // 上传图片
            $group_picssArr = [];
            $images = $request->file('group_picss');
            if (!empty($images)) {
                foreach ($images as $key => $val) {
                    $group_picssArr[$key] = AppProposalImage::proposalUploadPathToUrl($val->store(AppProposalImage::DEFAULT_UPLOAD_PATH_PREFIX));
                }
            }
            if (!empty($group_picssArr) && is_array($group_picssArr))
            {
                $collegeGroupPicsObj = new CollegeGroupPicsDao();
                foreach ($group_picssArr as $key => $val)
                {
                    $picsAddData['user_id'] = $user_id;
                    $picsAddData['group_id'] = $groupid;
                    $picsAddData['pics_bigurl'] = $val;
                    $picsAddData['pics_smallurl'] = $val;
                    $collegeGroupPicsObj->addCollegeGroupPicsInfo($picsAddData);
                }
            }
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败,请稍后重试');
        }
    }

    /**
     * Func 获取我的待审核
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_typeid']  是   类型(1:学生会,2:社团)
     *
     * @return Json
     */
    public function get_check_group_one_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_typeid = (Int)$request->input('group_typeid', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!in_array($group_typeid, [1, 2])) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;

        $collegeGroupobj = new CollegeGroupDao();
        $infos = $collegeGroupobj->getCheckGroupOneInfo($user_id,$group_typeid);
        if (!empty($infos)) {
            // 获取群组图片
            $collegeGroupPicsobj = new CollegeGroupPicsDao();
            $infos['picsList'] = $collegeGroupPicsobj->getCollegeGroupPicsListInfo($infos['groupid']);

            // 获取是否是最后一次
            $condition[] = ['user_id', '=', $user_id];
            $condition[] = ['group_typeid', '=', $group_typeid];
            $condition[] = ['groupid', '>', $infos['groupid']];
            if ($collegeGroupobj->getCollegeGroupCount($condition) > 0) {
                $infos = [];
            }
        }

        return JsonBuilder::Success ( $infos , '获取我的待审核' );

    }

    /**
     * Func 我的群组列表接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_typeid']  是   类型(1:学生会,2:社团)
     * @param['page']  是   分页id
     *
     * @return Json
     */
    public function get_group_list_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_typeid = (Int)$request->input('group_typeid', 0);
        $page = (Int)$request->input('page', 1);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!in_array($group_typeid, [1, 2])) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;

        $groupIdArr = []; // 存放groupid
        $infos = $infos1 = $infos2 = $infos3 = [];

        // 获取我发布的
        $condition1[] = ['group_typeid', '=', (Int)$group_typeid];
        $condition1[] = ['school_id', '=', (Int)$school_id];
        $condition1[] = ['user_id', '=', (Int)$user_id];
        $condition1[] = ['status', '=', 1];
        // 获取的字段
        $fieldArr1 = [
            'groupid' , 'user_id' , 'group_typeid' , 'group_pics' ,
            'group_title' , 'group_number' , 'group_time1',
        ];
        $infos1 = CollegeGroup::where($condition1)->select($fieldArr1)
            ->orderBy('group_time1', 'desc')
            ->get();
        $infos1 = !empty($infos1->toArray()) ? $infos1->toArray() : [];
        if (!empty($infos1)) {
            if ( $infos1 ) $groupIdArr = (array)array_column ( $infos1 ,'groupid');
        }

        // 获取我加入的社团
        $joinGroupIdListInfo = CollegeGroupJoin::whereNotIn('group_id', $groupIdArr)
            ->select(['group_id'])
            ->where('user_id', '=', $user_id)
            ->where('school_id','=',$school_id)
            ->where('status', '=', 1)
            ->orderBy('joinid', 'desc')
            ->get();
        $joinGroupIdListArr = !empty($joinGroupIdListInfo->toArray()) ? $joinGroupIdListInfo->toArray() : [];
        if ($joinGroupIdListArr) {
            // 获取的字段
            $fieldArr2 = [
                'groupid' , 'user_id' , 'group_typeid' , 'group_pics' ,
                'group_title' , 'group_number' , 'group_time1',
            ];
            $joinGroupIdArr = (array)array_column($joinGroupIdListArr, 'group_id');
            $infos2 = CollegeGroup::whereIn('groupid', $joinGroupIdArr)
                ->where('group_typeid', '=', $group_typeid)
                ->where('school_id', '=', $school_id)
                ->where('status', '=', 1)
                ->orderBy('group_time1', 'desc')
                ->select($fieldArr2)
                ->get();
            $infos2 = !empty($infos2->toArray()) ? $infos2->toArray() : [];
            if ($infos2) {
                $groupIdArr = array_merge((array)$groupIdArr, (array)array_column($infos2, 'groupid'));
            }
        }

        // 获取没参加的社团
        $fieldArr3 = [
            'groupid', 'user_id', 'group_typeid', 'group_pics',
            'group_title', 'group_number', 'group_time1',
        ];
        if (!empty($groupIdArr)) {
            $infos3 = CollegeGroup::whereNotIn('groupid', $groupIdArr)
                ->where('group_typeid', '=', $group_typeid)
                ->where('school_id','=',$school_id)
                ->where('status', '=', 1)
                ->orderBy('group_time1', 'desc')
                ->select($fieldArr3)
                ->get();
            $infos3 = !empty($infos3->toArray()) ? $infos3->toArray() : [];
        } else {
            $infos3 = CollegeGroup::where('group_typeid', '=', $group_typeid)
                ->where('school_id','=',$school_id)
                ->where('status', '=', 1)
                ->orderBy('group_time1', 'desc')
                ->select($fieldArr3)
                ->get();
            $infos3 = !empty($infos3->toArray()) ? $infos3->toArray() : [];
        }

        // 合并数据
        $infos = array_merge((array)$infos1, (array)$infos2, (array)$infos3);
        if (!empty($infos) && is_array($infos))
        {
            foreach ($infos as $key => &$val)
            {
                // 背景图片
                $val['group_pice1'] = '/Uploads/upload/20181027/1000X300_1.jpg';

                // 是否是管理员
                $val['is_manage'] = 0;
                if ($user_id == $val['user_id']) $val['is_manage'] = 1;

                // 是否有新的消息
                $val['is_message'] = 0;
                $getCollegeGroupJoinOneInfo = CollegeGroupJoin::where('group_id', '=', $val['groupid'])
                    ->where('user_id', '=', $user_id)
                    ->orderBy('joinid', 'desc')
                    ->first(['joinid','join_typeid', 'join_adminid', 'join_userid']);
                $getCollegeGroupJoinOneInfo = !empty($getCollegeGroupJoinOneInfo) ? $getCollegeGroupJoinOneInfo->toArray() : [];
                if ($getCollegeGroupJoinOneInfo) {
                    if ($getCollegeGroupJoinOneInfo['join_typeid'] == 1) {
                        $val['is_message'] = (Int)$getCollegeGroupJoinOneInfo['join_adminid'];
                    } else {
                        $val['is_message'] = (Int)$getCollegeGroupJoinOneInfo['join_userid'];
                    }
                }
            }
            $infos = array_chunk($infos, 15, true);
            $infos = array_values($infos[intval($page) - 1]);
        }
        return JsonBuilder::Success ( $infos , '我的群组列表接口' );
    }

    /**
     * Func 我的群组详情接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_id']  是   群组id
     *
     * @return Json
     */
    public function get_group_one_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_id = (Int)$request->input('group_id', 0);
        $page = (Int)$request->input('page', 1);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($group_id)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;

        $collegeGroupObj = new CollegeGroupDao();
        $collegeGroupJoinObj = new CollegeGroupJoinDao();
        $data = $collegeGroupObj->getCollegegroupOneInfo($group_id);
        if (!empty($data)) {
            // 获取用户基础信息
            $userInfo = $collegeGroupObj->userInfo($data['user_id']);

            // 获取粉丝和关注信息
            $getFansOrFocusOrPraiseNumber = $collegeGroupObj->getFansOrFocusOrPraiseNumber($data['user_id']);

            // 返回数据
            $infos['user_id'] = (Int)$userInfo['user_id'];  // 用户ID
            $infos['user_pics'] = (String)$userInfo['user_pics'];  // 头像
            $infos['user_nickname'] = (String)$userInfo['user_nickname']; // 姓名
            $infos['user_sex'] = (String)$userInfo['user_sex'];   // 性别
            $infos['user_signture'] = (String)$userInfo['user_signture']; // 签名
            $infos['user_fans_number'] = (Int)$getFansOrFocusOrPraiseNumber['user_fans_number']; // 粉丝数量
            $infos['user_focus_number'] = (Int)$getFansOrFocusOrPraiseNumber['user_focus_number']; // 关注数量
            $infos['user_praise_number'] = (Int)$getFansOrFocusOrPraiseNumber['user_praise_number'];  // 点赞数量
            $infos['group_title'] = (String)$data['group_title'];  // 社团名称
            $infos['group_pics'] = (String)$data['group_pics'];  // 社团图片

            // 获取用户背景图片
            $getUserColorInfo = $collegeGroupObj->getUserColorInfo($data['user_id']);
            $infos[ 'user_color_title' ] = (String)$getUserColorInfo[ 'user_color_title' ];  // 名称
            $infos[ 'user_color_small' ] = (String)$getUserColorInfo[ 'user_color_small' ];  // 小图
            $infos[ 'user_color_big' ] = (String)$getUserColorInfo[ 'user_color_big' ];  // 大图

            // 获取是否关注
            $infos['isfollow'] = 0;
            if ($userInfo['user_id'] != $user_id) {
                $userFollowObj = new UserFollowDao();
                $infos['isfollow'] = (Int)$userFollowObj->getUserFollowCount($user_id, $userInfo['user_id']);
            }

            // 获取我是否加入过
            // 状态(-1:待审核,1:审核通过,2:审核驳回)
            $infos['is_join'] = 0; // 未加入
            $infos['is_joinstr'] = '申请加入社群'; // 文字信息
            $getGroupidOrUseridJoinOneInfo = $collegeGroupJoinObj->getGroupidOrUseridJoinOneInfo($user_id, $data['groupid']);
            $statusMessageArr = [-1 => '审核中', 1 => '已加入', 2 => '审核中'];
            if ($getGroupidOrUseridJoinOneInfo && in_array($getGroupidOrUseridJoinOneInfo['status'], [-1, 1, 2])) {
                $infos['is_join'] = $getGroupidOrUseridJoinOneInfo['status'];
                $infos['is_joinstr'] = (String)$statusMessageArr[$getGroupidOrUseridJoinOneInfo['status']];
            }

            // 获取社团成员
            $infos['memberList'] = $collegeGroupJoinObj->getCollegeGroupJoinGroupListInfo($data['groupid'], 1, 100);
            $infos['member_count'] = count($infos['memberList']);
            if ($infos['memberList']) {
                foreach ($infos['memberList'] as $key => &$val) {
                    $userInfo1 = $collegeGroupObj->userInfo($data['user_id']);
                    $val['user_pics'] = (String)$userInfo1['user_pics'];
                    $val['user_nickname'] = $val['join_typeid'] == 1 ? '团长' : $val['user_nickname'];
                }
            }

            // 获取公告信息
            $collegeGroupNoticesObj = new CollegeGroupNoticesDao();
            $infos['noticeList'] = $collegeGroupNoticesObj->getCollegeGroupNoticesListInfo($data['groupid'], 1, 1);
            if (!empty($infos['noticeList'])) {
                foreach ($infos['noticeList'] as $key => &$val) {
                    // 获取公告信息
                    $val['user_pics'] = (String)$data['group_pics'];
                    $val['user_nickname'] = (String)$data['group_title'];
                    // 格式化时间
                    $val['create_timestr'] = $collegeGroupNoticesObj->transTime1(strtotime($val['created_at']));
                }
            }

            // 获取动态列表
            $condition2[] = ['minx_id', '=', (Int)$data['groupid']];
            $condition2[] = ['user_id', '=', (Int)$user_id];
            $condition2[] = ['cate_id', '=', 2];
            $condition2[] = ['status', '=', 1];
            // 获取的数据字段
            $fieldArr2 = [
                'icheid', 'user_id', 'school_id', 'iche_type', 'iche_title', 'iche_content',
                'iche_view_num', 'iche_share_num', 'iche_praise_num', 'iche_comment_num',
                'iche_view_num', 'status', 'created_at'
            ];
            $dynamicInfo = Affiche::where($condition2)
                ->orderBy('icheid', 'desc')
                ->offset($collegeGroupNoticesObj->offset($page))
                ->limit($collegeGroupNoticesObj::$limit)
                ->select($fieldArr2)
                ->get();
            $infos['dynamicList'] = !empty($dynamicInfo->toArray()) ? $dynamicInfo->toArray() : [];
            if (!empty($infos['dynamicList']))
            {
                foreach ( $infos['dynamicList'] as $key => &$val )
                {
                    // TODO.....获取分享url
                    //$val = $model->returnAfficheOneInfo ( $val , $authUser[ 'userid' ] );
                    //$val[ 'shareulr' ] = $this->getShareUrlInfo ( 3 , $val[ 'icheid' ] );
                    $val[ 'shareulr' ] = '';
                    // 获取是否点赞
                    $praiseobj = new PraiseDao();
                    $val['ispraise'] = (Int)$praiseobj->getAffichePraiseCount(1, $user_id, $val['icheid']);
                    // 处理字符串
                    $val[ 'iche_content' ] = $val[ 'iche_content' ] ? $val[ 'iche_content' ] : '';
                    // 姓名
                    $val[ 'user_pics' ]     = (String)$data[ 'group_pics' ];
                    $val[ 'user_nickname' ] = (String)$data[ 'group_title' ];
                    // 格式化时间
                    $val['create_timestr'] = $praiseobj->transTime1(strtotime($val['created_at']));
                }
            }
        }

        // 更新为已读状态
        $condtion3[] = ['user_id', '=', $user_id];
        $condtion3[] = ['group_id', '=', $data['groupid']];
        if ($user_id == $data['user_id'])
        {
            $saveData['join_adminid'] = 0;
            CollegeGroupJoin::where($condtion3)->update($saveData);
        } else {
            $saveData['join_userid'] = 0;
            CollegeGroupJoin::where($condtion3)->update($saveData);
        }

        return JsonBuilder::Success ( $infos , '我的群组详情接口' );
    }

    /**
     * Func 加入群组接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_id']  是   群组id
     * @param['join_apply_desc1']  是   加入申请描述
     *
     * @return Json
     */
    public function join_group_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_id = (Int)$request->input('group_id', 0);
        $join_apply_desc1 = (String)$request->input('join_apply_desc1', '');

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($group_id)) {
            return JsonBuilder::Error('参数错误');
        }
        if (!trim($join_apply_desc1)) {
            return JsonBuilder::Error('申请描述不能为空');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        // TODO.....验证是否入驻
 /*       if (!$user_usercode) {
            return api_response_error('', '您还未认证学生信息,认证通过后可申请加入。');
        }
 */
        // 获取群组详情
        $collegeGroupobj = new CollegeGroupDao();
        $getCollegegroupOneInfo = $collegeGroupobj->getCollegegroupOneInfo($group_id);
        if (empty($getCollegegroupOneInfo) || $getCollegegroupOneInfo['status'] != 1) {
            return JsonBuilder::Error('信息不存在');
        }

        // 查询我是否加入群组
        $collegeGroupJoinObj = new CollegeGroupJoinDao();
        $getGroupidOrUseridJoinOneInfo = $collegeGroupJoinObj->getGroupidOrUseridJoinOneInfo(
            $user_id, $getCollegegroupOneInfo['groupid']
        );
        // 状态(-1:待审核,1:审核通过,2:审核驳回)
        $messageArr = [-1 => '申请审核中,不能再次申请。', 1 => '你已加入，不能再次申请。'];
        if ($getGroupidOrUseridJoinOneInfo && in_array($getGroupidOrUseridJoinOneInfo['status'], [-1, 1])) {
            return JsonBuilder::Error($messageArr[$getGroupidOrUseridJoinOneInfo['status']]);
        }

        // 添加数据
        $addData['group_id'] = $getCollegegroupOneInfo['groupid'];
        $addData['user_id'] = $user_id; // 用户id
        $addData['school_id'] = $school_id; // 学校id
        $addData['campus_id'] = $campus_id; // 校区id
        $addData['join_typeid'] = 4; // 角色(1:管理员,2:会长,3:秘书长,4:普通用户)
        $addData['join_userid'] = 0; // 是否查看(1:未查看,0:已查看)
        $addData['join_adminid'] = 1; // 管理员是否读取(1:未查看,0:已查看)
        $addData['join_apply_desc1'] = (String)trim($join_apply_desc1);
        if ($collegeGroupJoinObj->addCollegeGroupJoinInfo($addData)) {
            return JsonBuilder::Success('申请成功，等待审核。');
        } else {
            return JsonBuilder::Error('操作失败,请稍后重试');
        }
    }

    /**
     * Func 获取群组所有用户接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_id']  是   群组id
     *
     * @return Json
     */
    public function get_group_more_member_list_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_id = (Int)$request->input('group_id', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($group_id)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        // 获取群组详情
        $collegeGroupobj = new CollegeGroupDao();
        $infos = $collegeGroupobj->getCollegegroupOneInfo($group_id);
        if (!empty($infos))
        {
            // 查询我是否加入群组
            $condition1[] = ['group_id', '=', (Int)$infos['groupid']];
            $condition1[] = ['status', '=', 1];
            // 获取的字段
            $fieldArr1 = ['joinid', 'user_id', 'join_typeid', 'join_apply_desc1 as user_nickname'];
            $data1 = CollegeGroupJoin::where($condition1)
                ->orderBy('join_typeid', 'asc')
                ->orderBy('joinid', 'desc')
                ->select($fieldArr1)
                ->get();
            $infos['dataList1'] = !empty($data1->toArray()) ? $data1->toArray() : [];
            if (!empty($infos['dataList1']) && is_array($infos['dataList1']))
            {
                foreach ($infos['dataList1'] as $key => &$val)
                {
                    $userInfo1 = $collegeGroupobj->userInfo($val['user_id']);
                    $val['user_nickname'] = $user_id != $val['user_id'] ? $val['user_nickname'] : '团长';
                    $val['user_pics'] = $userInfo1['user_pics'];
                }
            }

            // 获取待审核的用户
            $condition2[] = ['group_id', '=', (Int)$infos['groupid']];
            $condition2[] = ['status', '=', -1];
            // 获取的字段
            $fieldArr2 = ['joinid', 'user_id', 'join_typeid', 'join_apply_desc1 as user_nickname'];
            $data2 = CollegeGroupJoin::where($condition2)
                ->orderBy('join_typeid', 'asc')
                ->orderBy('joinid', 'desc')
                ->select($fieldArr1)
                ->get();
            $infos['dataList2'] = !empty($data2->toArray()) ? $data2->toArray() : [];
            if (!empty($infos['dataList2']) && is_array($infos['dataList2']))
            {
                foreach ($infos['dataList2'] as $key => &$val)
                {
                    $userInfo2 = $collegeGroupobj->userInfo($val['user_id']);
                    $val['user_nickname'] = $user_id != $val['user_id'] ? $val['user_nickname'] : '团长';
                    $val['user_pics'] = $userInfo2['user_pics'];
                }
            }
        }
        return JsonBuilder::Success($infos, '获取群组全部用户');
    }

    /**
     * Func 获取群组已通过用户接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_id']  是   群组id
     *
     * @return Json
     */
    public function get_group_passed_member_list_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_id = (Int)$request->input('group_id', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($group_id)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        // 获取群组详情
        $collegeGroupobj = new CollegeGroupDao();
        $infos = $collegeGroupobj->getCollegegroupOneInfo($group_id);
        if (!empty($infos))
        {
            // 查询我是否加入群组
            $condition[] = ['group_id', '=', (Int)$infos['groupid']];
            $condition[] = ['status', '=', 1];
            // 获取的字段
            $fieldArr = ['joinid', 'user_id', 'join_typeid', 'join_apply_desc1 as user_nickname'];
            $data = CollegeGroupJoin::where($condition)
                ->orderBy('join_typeid', 'asc')
                ->orderBy('joinid', 'desc')
                ->select($fieldArr)
                ->get();
            $infos['dataList'] = !empty($data->toArray()) ? $data->toArray() : [];
            if (!empty($infos['dataList']) && is_array($infos['dataList']))
            {
                foreach ($infos['dataList'] as $key => &$val)
                {
                    $userInfo1 = $collegeGroupobj->userInfo($val['user_id']);
                    $val['user_nickname'] = $user_id != $val['user_id'] ? $val['user_nickname'] : '团长';
                    $val['user_pics'] = $userInfo1['user_pics'];
                }
            }
        }
        return JsonBuilder::Success($infos, '获取群组已通过用户');
    }

    /**
     * Func 获取群组待审核用户接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_id']  是   群组id
     *
     * @return Json
     */
    public function get_group_pending_member_list_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_id = (Int)$request->input('group_id', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($group_id)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        // 获取群组详情
        $collegeGroupobj = new CollegeGroupDao();
        $infos = $collegeGroupobj->getCollegegroupOneInfo($group_id);
        if (!empty($infos))
        {
            // 查询我是否加入群组
            $condition[] = ['group_id', '=', (Int)$infos['groupid']];
            $condition[] = ['status', '=', -1];
            // 获取的字段
            $fieldArr = ['joinid', 'user_id', 'join_typeid', 'join_apply_desc1 as user_nickname'];
            $data = CollegeGroupJoin::where($condition)
                ->orderBy('join_typeid', 'asc')
                ->orderBy('joinid', 'desc')
                ->select($fieldArr)
                ->get();
            $infos['dataList'] = !empty($data->toArray()) ? $data->toArray() : [];
            if (!empty($infos['dataList']) && is_array($infos['dataList']))
            {
                foreach ($infos['dataList'] as $key => &$val)
                {
                    $userInfo1 = $collegeGroupobj->userInfo($val['user_id']);
                    $val['user_pics'] = $userInfo1['user_pics'];
                }
            }
        }
        return JsonBuilder::Success($infos, '获取群组待审核用户');
    }

    /**
     * Func 审核单个用户加入群组接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_id']  是   群组id
     * @param['joinids']  是   加入ID,Json格式例如：[1,2,3]
     * @param['status']  是   状态(1:同意,2:不同意)
     *
     * @return Json
     */
    public function edit_group_member_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_id = (Int)$request->input('group_id', 0);
        $joinids = (String)$request->input('joinids', '');
        $status = (Int)$request->input('status', 0);
        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($group_id)) {
            return JsonBuilder::Error('参数错误');
        }
        if (!in_array($status, [1, 2])) {
            return JsonBuilder::Error('状态值错误');
        }
        // 审核的加入id
        $joinidArr = json_decode(htmlspecialchars_decode($joinids), true);
        if (empty($joinidArr)) {
            return JsonBuilder::Error('请选择要审核的用户');
        }

        $user = $request->user();
        $user_id = $user->id;

        // 获取我是否有权限
        $collegeGroupJoinObj = new CollegeGroupJoinDao();
        $getGroupidOrUseridJoinOneInfo = $collegeGroupJoinObj->getGroupidOrUseridJoinOneInfo($user_id, $group_id);
        if (empty($getGroupidOrUseridJoinOneInfo) || $getGroupidOrUseridJoinOneInfo['join_typeid'] != 1) {
           // return JsonBuilder::Error('你无权操作');
        }

        // 处理审核数据
        foreach ( $joinidArr as $key => $val )
        {
            // 审核描述信息
            $messageArr = [ 1 => '审核通过' , 2 => '审核未通过' ];

            // 检索条件
            $condition = [];
            $condition[] = ['joinid', '=', (Int)$val];
            $condition[] = ['group_id', '=', $group_id];
            $condition[] = ['join_typeid', '=', 4];
            $condition[] = ['status', '=', -1];

            // 审核通过
            if ($status == 1)
            {
                // 更新的内容
                $saveData = [];
                $saveData['status'] = $status;
                $saveData['join_userid'] = 1; // 是否查看(1:未查看,0:已查看)
                $saveData['join_apply_desc2'] = $messageArr[$status];
                CollegeGroupJoin::where($condition)->update($saveData);

                // 重新计算数据
                (new CollegeGroupDao())->calculateCollegeGroupInfo($group_id);
            }

            // 审核未通过直接删除
            if ($status == 2) {
                CollegeGroupJoin::where($condition)->delete();
            }
        }
        return JsonBuilder::Success('操作成功');
    }

    /**
     * Func 删除群组中单个用户接口
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['joinid']  是 加入ID
     *
     * @return Json
     */
    public function del_group_member_info(GroupRequest $request)
    {
        $token = (String)$request->input('token', '');
        $joinid = (String)$request->input('joinid', 0);
        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($joinid)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;

        // 获取要删除的数据
        $joinOneInfo = CollegeGroupJoin::where('joinid', '=', $joinid)->first(['*']);
        $joinOneArr = !empty($joinOneInfo) ? $joinOneInfo->toArray() : [];
        if (empty($joinOneArr)) {
            return JsonBuilder::Error('信息不存在');
        }
        if ($joinOneArr['join_typeid'] == 1) {
            return JsonBuilder::Error('无法删除管理员');
        }

        // 验证我是否有权限删除
        $condition[] = ['group_id', '=', $joinOneArr['group_id']];
        $condition[] = ['user_id', '=', $user_id];
        $condition[] = ['join_typeid', '=', 1];
        if (CollegeGroupJoin::where($condition)->count()) {
            CollegeGroupJoin::where('joinid', '=', $joinid)->delete();
            // 重新计算数据
            (new CollegeGroupDao())->calculateCollegeGroupInfo($joinOneArr['group_id']);
        }

        return JsonBuilder::Success('操作成功');
    }

}
