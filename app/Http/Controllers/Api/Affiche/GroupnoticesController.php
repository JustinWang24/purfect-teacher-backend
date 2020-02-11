<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/31
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Affiche;

use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Affiche\GroupnoticeRequest;

use App\Dao\Users\UserDao;
use App\Dao\Affiche\Api\CollegeGroupDao;
use App\Dao\Affiche\Api\CollegeGroupJoinDao;
use App\Dao\Affiche\Api\CollegeGroupNoticesDao;
use App\Dao\Affiche\Api\CollegeGroupNoticeReaderDao;

use App\Models\Affiche\CollegeGroupJoin;
use App\Models\Affiche\CollegeGroupNoticeReader;
use Illuminate\Support\Facades\DB;
use App\BusinessLogic\WifiInterface\Factory;

class GroupnoticesController extends Controller
{
    /**
     * Func 群添加公告
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_id']  是   群id
     * @param['notice_content']  是   公告内容
     *
     * @return Json
     */
    public function add_groupnotices_info(GroupnoticeRequest $request)
    {
        $token = (String)$request->input('token', '');
        $group_id = (Int)$request->input('group_id', 0);
        $notice_content = (String)$request->input('notice_content', '');

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($group_id)) {
            return JsonBuilder::Error('参数错误');
        }
        if (!trim($notice_content) ) {
            return JsonBuilder::Error('公告内容不能为空');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        // 获取我是否有数据
        $collegeGroupObj = new CollegeGroupDao();
        $data = $collegeGroupObj->getCollegegroupOneInfo($group_id);

        if (empty($data) || $data['user_id'] != $user_id) {
            return JsonBuilder::Error('你无权发布');
        }
        if ($data['status'] == -1) {
            return JsonBuilder::Error('您的【' . $data['group_title'] . '】还在审核中,不能发布公告');
        }
        if ($data['status'] == 2) {
            return JsonBuilder::Error('您的【' . $data['group_title'] . '】审核未通过,不能发布公告');
        }

        // 添加数据
        $addData['user_id'] = $user_id;
        $addData['school_id'] = $school_id;
        $addData['campus_id'] = $campus_id;
        $addData['group_id'] = $data['groupid'];
        $addData['notice_content'] = trim($notice_content);
        if ($noticeid = (new CollegeGroupNoticesDao())->addCollegeGroupNoticesInfo($addData)) {
            // 添加红点标注
            $condtion[] = ['group_id', '=', $data['groupid']];
            $condtion[] = ['user_id', '!=', $user_id];
            $condtion[] = ['status', '=', 1];
            CollegeGroupJoin::where($condtion)->update(['join_userid' => 1]);

            return JsonBuilder::Success('操作成功');

        } else {
            return JsonBuilder::Error('操作失败,请稍后重试');
        }
    }

    /**
     * Func 公告列表
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['group_id']  是   群id
     * @param['notice_content']  是   公告内容
     *
     * @return Json
     */
    public function list_groupnotices_info(GroupnoticeRequest $request)
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

        // 获取我是否有数据
        $collegeGroupObj = new CollegeGroupDao();
        $data = $collegeGroupObj->getCollegegroupOneInfo($group_id);

        // 返回数据
        $infos['is_manage'] = 0;
        $infos['dataList'] = [];
        if (!empty($data)) {
            // 是否是管理员
            if ($user_id == $data['user_id']) $infos['is_manage'] = 1;
            $collegeGroupNoticesObj = new CollegeGroupNoticesDao();
            $infos['dataList'] = $collegeGroupNoticesObj->getCollegeGroupNoticesListInfo($data['groupid'], $page);
            if (!empty($infos['dataList'])) {
                foreach ($infos['dataList'] as $key => &$val) {
                    // 团长姓名
                    $val['user_nickname'] = (String)$data['group_title'];
                    $val['user_pics'] = (String)$data['group_pics'];
                    // 格式化时间
                    $val['create_timestr'] = $collegeGroupNoticesObj->transTime1(strtotime($val['created_at']));
                }
            }
        }
        return JsonBuilder::Success($infos,'公告列表');
    }

    /**
     * Func 公告详情
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['noticeid']  是   公告id
     *
     * @return Json
     */
    public function one_groupnotices_info(GroupnoticeRequest $request)
    {
        $token = (String)$request->input('token', '');
        $noticeid = (Int)$request->input('noticeid', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($noticeid)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        // 获取公告信息
        $collegeGroupNoticesObj = new CollegeGroupNoticesDao();
        $infos = $collegeGroupNoticesObj->getCollegeGroupNoticesOneInfo($noticeid);
        if (!empty($infos)) {
            // 获取群基础信息
            $collegeGroupObj = new CollegeGroupDao();
            $data = $collegeGroupObj->getCollegegroupOneInfo($infos['group_id']);
            $infos['user_nickname'] = (String)$data['group_title'];
            $infos['user_pics'] = (String)$data['group_pics'];

            // 获取我申请加入的信息
            $collegeGroupJoinObj = new CollegeGroupJoinDao();
            $groupJoinOneInfo = $collegeGroupJoinObj->getGroupidOrUseridJoinOneInfo($user_id, $infos['group_id']);

            // 更新公告用户已经查看的操作
            $collegeGroupNoticeReaderObj = new CollegeGroupNoticeReaderDao();
            $noticeReaderOneInfo = $collegeGroupNoticeReaderObj->getCollegeGroupNoticeReaderOneInfo($user_id, $noticeid);
            if (empty($noticeReaderOneInfo))
            {
                $addData1['user_id'] = $user_id;
                $addData1['notice_id'] = $noticeid;
                $addData1['group_id'] = $infos['group_id'];
                $addData1['school_id'] = $school_id;
                $addData1['campus_id'] = $campus_id;
                $addData1['notice_apply'] = !empty($groupJoinOneInfo) ? 1 : 0;
                $collegeGroupNoticeReaderObj->addCollegeGroupNoticeReaderInfo($addData1);
            } else {
                $saveData1['notice_apply'] = !empty($groupJoinOneInfo) ? 1 : 0;
                $collegeGroupNoticeReaderObj->editCollegeGroupNoticeReaderInfo($saveData1, $noticeReaderOneInfo['readerid']);
            }

            // 所有查看重新计算公告查看数
            $condition5[] = ['notice_id', '=', $infos['noticeid']];
            $condition5[] = ['group_id', '=', $infos['group_id']];
            $condition5[] = ['notice_apply', '=', 0];
            $condition5[] = ['status', '=', 1];

            // 社团查看人数重新计算公告查看数
            $condition6[] = ['notice_id', '=', $infos['noticeid']];
            $condition6[] = ['group_id', '=', $infos['group_id']];
            $condition6[] = ['notice_apply', '=', 1];
            $condition6[] = ['status', '=', 1];

            // 更新动态查看数
            $saveData1['notice_number1'] = $collegeGroupNoticeReaderObj->getCollegeGroupNoticeReaderCount($condition5);
            $saveData1['notice_number2'] = $collegeGroupNoticeReaderObj->getCollegeGroupNoticeReaderCount($condition6);
            $collegeGroupNoticesObj->editCollegeGroupNoticesInfo($saveData1, $infos['noticeid']);

            // 是否是管理员
            $infos[ 'is_manage' ] = $user_id == $infos[ 'user_id' ] ? 1 : 0;
            // 时间戳格式化
            $infos['create_timestr'] = $collegeGroupNoticesObj->transTime1(strtotime($infos['created_at']));
        }
        return JsonBuilder::Success($infos,'公告详情');
    }

    /**
     * Func 公告已读和未读
     *
     * @param Request $request
     * @param['token']  是   token
     * @param['noticeid']  是   公告id
     *
     * @return Json
     */
    public function unreadOrread_groupnotices_info(GroupnoticeRequest $request)
    {
        $token = (String)$request->input('token', '');
        $noticeid = (Int)$request->input('noticeid', 0);

        if ($token == '') {
            return JsonBuilder::Error('请先登录');
        }
        if (!intval($noticeid)) {
            return JsonBuilder::Error('参数错误');
        }

        $user = $request->user();
        $user_id = $user->id;
        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        // 返回数据 number1 已读  number2 未读
        $infos[ 'number1' ]     = $infos[ 'number2' ] = 0;
        $infos[ 'number1List' ] = $infos[ 'number2List' ] = [];

        // 获取公告信息
        $collegeGroupNoticesObj = new CollegeGroupNoticesDao();
        $data = $collegeGroupNoticesObj->getCollegeGroupNoticesOneInfo($noticeid);

        if (!empty($data)) {
            // 获取我申请加入的信息
            $numberArr = [];
            $collegeGroupJoinObj = new CollegeGroupJoinDao();
            $groupJoinOneInfo = $collegeGroupJoinObj->getCollegeGroupJoinGroupListInfo($data['group_id'], 1, 300);
            if (!empty($groupJoinOneInfo)) {
                $numberArr = array_column($groupJoinOneInfo, 'user_id');
            }
            if (!empty($numberArr))
            {
                // 更新公告用户已经查看的操作
                $number1List = CollegeGroupNoticeReader::where('group_id', '=', $data['group_id'])
                    ->where('notice_id', '=', $data['noticeid'])
                    ->whereIn('user_id', $numberArr)
                    ->select(['user_id'])
                    ->get();

                $infos['number1List'] = !empty($number1List->toArray()) ? $number1List->toArray() : [];
                $infos['number1'] = count($infos['number1List']);

                if (!empty($infos['number1List']))
                {
                    foreach ($infos['number1List'] as $key => &$val)
                    {
                        // 获取申请信息
                        $joinInfo = $collegeGroupJoinObj->getGroupidOrUseridJoinOneInfo($val['user_id'], $data['group_id']);
                        $val['user_nickname'] = !empty($joinInfo['join_apply_desc1']) ? $joinInfo['join_apply_desc1'] : '';
                        $val['join_typeid'] = !empty($joinInfo['join_typeid']) ? $joinInfo['join_typeid'] : 0;

                        // 用户信息
                        $userInfo = $collegeGroupJoinObj->userInfo($val['user_id']);
                        $val['user_pics'] = $userInfo['user_pics'];
                        // 用户姓名
                        $val['user_nickname'] = $val['join_typeid'] == 1 ? '团长' : $val['user_nickname'];
                    }

                    // 更新公告用户已经查看的操作
                    $number2List = CollegeGroupNoticeReader::where('group_id', '=', $data['group_id'])
                        ->whereNotIn('user_id', (array)array_column($infos['number1List'], 'user_id'))
                        ->select(['user_id'])
                        ->get();

                    $infos['number2List'] = !empty($number2List->toArray()) ? $number2List->toArray() : [];
                    $infos['number2'] = count($infos['number2List']);
                    if (!empty($infos['number2List']))
                    {
                        foreach ($infos['number2List'] as $key => &$val)
                        {
                            // 获取申请信息
                            $joinInfo = $collegeGroupJoinObj->getGroupidOrUseridJoinOneInfo($val['user_id'], $val['group_id']);
                            $val['user_nickname'] = !empty($joinInfo['join_apply_desc1']) ? $joinInfo['join_apply_desc1'] : '';
                            $val['join_typeid'] = !empty($joinInfo['join_typeid']) ? $joinInfo['join_typeid'] : 0;

                            // 用户信息
                            $userInfo = $collegeGroupJoinObj->userInfo($val['user_id']);
                            $val['user_pics'] = $userInfo['user_pics'];

                            $val['user_nickname'] = $val['join_typeid'] == 1 ? '团长' : $val['user_nickname'];
                        }
                    }
                }
            }
        }

        return JsonBuilder::Success($infos,'公告已读和未读');
    }
}
