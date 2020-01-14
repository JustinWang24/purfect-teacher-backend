<?php
/**
 * Created by PhpStorm.
 * User: zhang.kui
 * Date: 20/01/11
 * Time: 11:33 AM
 */
namespace App\Http\Controllers\Api\Welcome;

use App\Dao\Welcome\Api\WelcomeConfigDao;
use App\Dao\Welcome\Api\WelcomeUserReportDao;

use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Welcome\IndexRequest;
use App\Models\Notices\AppProposalImage;
use Illuminate\Support\Facades\DB;
class IndexController extends Controller
{


    /**
     * Func 迎新首页
     * @param Request $request
     * @return Json
     */
    public function index(IndexRequest $request)
    {
        /*
        $data = array(
            array('name'=>'来校报到','data'=>array(array('name'=>'个人信息','icon'=>''),array('name'=>'报到扫码','icon'=>''))),
            array('name'=>'个人凭证','data'=>array(array('name'=>'报到单','icon'=>''))),
        );
        echo json_encode($data);exit;*/

        $user = $request->user();

        $user_id = $user->id;

        $campus_id = $user->gradeUser->campus_id;

        if (!intval($campus_id) || !intval($user_id)) {
            return JsonBuilder::Error('参数错误');
        }

        // 获取是否已完善信息
        $WelcomeUserReportDao = new  WelcomeUserReportDao();

        $getWelcomeUserReportOneInfoField = ['flow_id', 'status'];

        $getWelcomeUserReportOneInfo = $WelcomeUserReportDao->getWelcomeUserReportOneInfo($user_id,$getWelcomeUserReportOneInfoField);

        if (empty($getWelcomeUserReportOneInfo) || $getWelcomeUserReportOneInfo->status != 1)
        {
            // 获取迎新配置信息
            $checkUserIsWelcomeInfo = $WelcomeUserReportDao->checkUserIsWelcomeInfo($campus_id, $user_id);

            // 迎新已完成
            $data['status'] = $checkUserIsWelcomeInfo['status'];
            $data['message'] = $checkUserIsWelcomeInfo['message'];

        } else {
            // 迎新已完成
            $data['status'] = 1;
            $data['message'] = '已完成';
        }

        // 获取配置信息
        $infos = [];

        $WelcomeConfigDao = new WelcomeConfigDao();

        $getWelcomeConfigOneInfo = $WelcomeConfigDao->getWelcomeConfigOneInfo( $campus_id , $fieldArr = ['config_menu'] );

        if(!empty($getWelcomeConfigOneInfo) && $getWelcomeConfigOneInfo->config_menu)
        {
            $infos['menu'] = json_decode($getWelcomeConfigOneInfo->config_menu,true);

            if(is_array($infos['menu']) && count($infos['menu']) >  0 )
            {
                // 菜单只分二级，不能在三级了。
                foreach($infos['menu'] as $key=>&$val)
                {
                    foreach($val['data'] as $k=>&$v)
                    {
                        // 个人信息(A)
                        if($v['letter'] == 'A')
                        {
                            $v['notice'] = $data['status'] == 1 ? '已完成' : '';
                            $v['status'] = $data['status'] == 1 ? 1: 2;
                            $v['message'] = '';
                        }

                        // 报到扫码(B)
                        if($v['letter'] == 'B')
                        {
                            $v['notice'] = $data['status'] == 1 ? '已完成' : '';
                            $v['status'] = $data['status'] == 1 ? 1 : 0;
                            $v['message'] = $data['status'] == 1 ? '' : $data['message'];
                        }

                        // 报到单(C)
                        if($v['letter'] == 'C')
                        {
                            $v['notice'] = '';
                            $v['status'] = $data['status'] == 1 ? 1 : 0;
                            $v['message'] = $data['status'] == 1 ? '' : $data['message'];
                        }
                    }
                }
            }

        }


        return JsonBuilder::Success($infos, '迎新首页');
    }

    /**
     * Func 个人信息(编辑页)
     * @param Request $request
     * @return Json
     */
    public function base_user_info(IndexRequest $request)
    {
        $user = $request->user();

        $user_id = $user->id;

        $campus_id = $user->gradeUser->campus_id;

        if (!intval($campus_id) || !intval($user_id)) {
            return JsonBuilder::Error('参数错误');
        }

        // 获取用户基础信息
        $WelcomeUserReportDao = new WelcomeUserReportDao();

        $infos = $WelcomeUserReportDao->getUserReportOrUserProfilesInfo($user_id);

        return JsonBuilder::Success($infos, '个人信息(编辑页)');
    }

    /**
     * Func 个人信息(编辑页)保存
     * @param Request $request
     * @return Json
     */
    public function save_user_info(IndexRequest $request)
    {
        $user = $request->user();

        $user_id = $user->id;

        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        $param = $request->only(
            [
                'steps_1_str'
            ]
        );

        if (!intval($user_id) || !intval($school_id) || !intval($campus_id)) {
            return JsonBuilder::Error('参数错误');
        }

        // 验证数据
        $steps_1_Arr = json_decode($param['steps_1_str'], true);
        if (empty($steps_1_Arr) || count($steps_1_Arr) <= 0) {
            return JsonBuilder::Error('数据格式错误');
        }

        // 添加或更新数据
        $data['flow_id'] = 1; //TODO.....
        $data['user_id'] = $user_id;
        $data['school_id'] = $school_id;
        $data['campus_id'] = $campus_id;
        $data['steps_1_str'] = json_encode($steps_1_Arr);
        $data['steps_1_date'] = date('Y-m-d H:i:s');

        // 获取用户基础信息
        $WelcomeUserReportDao = new WelcomeUserReportDao();

        $getWelcomeUserReportOneInfo = $WelcomeUserReportDao->getWelcomeUserReportOneInfo($user_id);

        // 添加
        if(empty($getWelcomeUserReportOneInfo))
        {
            if ($WelcomeUserReportDao->saveWelcomeUserReportsInfo($data) != false)
            {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        } else { // 更新
            if ($WelcomeUserReportDao->updateWelcomeUserReportsInfo($data, $getWelcomeUserReportOneInfo->configid) != false)
            {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        }
    }

    /**
     * Func 上传照片信息
     * @param Request $request
     * @return Json
     */
    public function save_photo_info(IndexRequest $request)
    {
        $user = $request->user();

        $user_id = $user->id;

        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        if (!intval($user_id) || !intval($school_id) || !intval($campus_id)) {
            return JsonBuilder::Error('参数错误');
        }

        // 上传图片
        $path = [];
        $images = $request->file('image');
        if (!empty($images)) {
            foreach ($images as $key=>$val) {
                $path[$key] = AppProposalImage::proposalUploadPathToUrl($val->store(AppProposalImage::DEFAULT_UPLOAD_PATH_PREFIX));
            }
        }
        if(empty($path['photo']))
        {
            return JsonBuilder::Error('请上传照片');
        }
        if(empty($path['card_front']))
        {
            return JsonBuilder::Error('请上传身份证正面照片');
        }
        if(empty($path['card_reverse']))
        {
            return JsonBuilder::Error('请上传身份证反面照片');
        }

        // 更新数据
        $picsArr = [
            'photo' => $path['photo'], // 照片
            'card_front' => $path['card_front'], // 身份证正面照片
            'card_reverse' => $path['card_reverse'] // 身份证反面照片
        ];

        // 添加或更新数据
        $data['status'] = 1;
        $data['steps_2_str'] = json_encode($picsArr);
        $data['steps_2_date'] = date('Y-m-d H:i:s');
        $data['complete_date'] = date('Y-m-d H:i:s');

        // 获取用户基础信息
        $WelcomeUserReportDao = new WelcomeUserReportDao();
        $getWelcomeUserReportOneInfo = $WelcomeUserReportDao->getWelcomeUserReportOneInfo($user_id);

        // 验证基础信息是否完善
        if(empty($getWelcomeUserReportOneInfo))
        {
            return JsonBuilder::Error('请先完善个人信息');
        }

        // 更新数据
        if ($WelcomeUserReportDao->updateWelcomeUserReportsInfo($data, $getWelcomeUserReportOneInfo->configid) != false)
        {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败,请稍后重试');
        }

    }

    /**
     * Func 个人信息(已确认)
     * @param Request $request
     * @return Json
     */
    public function confirm_user_info(IndexRequest $request)
    {
        $user = $request->user();

        $user_id = $user->id;

        $campus_id = $user->gradeUser->campus_id;

        if (!intval($campus_id) || !intval($user_id)) {
            return JsonBuilder::Error('参数错误');
        }

        // 获取用户基础信息
        $WelcomeUserReportDao = new WelcomeUserReportDao();

        // 基础信息
        $data = $WelcomeUserReportDao->getWelcomeUserReportOneInfo($user_id);

        // 获取
        $infos['status'] = $data->status == 1 ? 1 : 2; // 状态(0:关闭，1:已完成，2:未完成)
        $infos['steps_1_Arr'] = $data->steps_1_str ? json_decode($data->steps_1_str, true) : [];
        $infos['steps_2_Arr'] = $data->steps_2_str ? json_decode($data->steps_2_str, true) : [];

        return JsonBuilder::Success($infos, '个人信息(已确认)');
    }

    /**
     * Func 报到确认
     * @param Request $request
     * @return Json
     */
    public function confirm_report_info(IndexRequest $request)
    {
        $user = $request->user();

        $user_id = $user->id;

        $campus_id = $user->gradeUser->campus_id;

        if (!intval($campus_id) || !intval($user_id)) {
            return JsonBuilder::Error('参数错误');
        }


        // 获取报到单信息
        $WelcomeUserReportDao = new WelcomeUserReportDao();

        $data = $WelcomeUserReportDao->getUserReportOrUserProfilesInfo($user_id);

        // 基本信息
        $infos['uerIno'] = [
            array(
                array('title'=>'姓名','value'=>$data['user_name']),
                array('title'=>'身份证','value'=>$data['id_number']),
                array('title'=>'学院','value'=>$data['institute_name']),
                array('title'=>'专业','value'=>$data['major_name']),
            )
        ];

        // 二维码
        //TODO......
        $infos['qrcode'] = '/assets/img/qrcode/erweim.png';

        // 获取报到时间
        $infos['complete_date'] = '';

        $getWelcomeUserReportOneInfo = $WelcomeUserReportDao->getWelcomeUserReportOneInfo($user_id,['complete_date']);

        if (!empty($getWelcomeUserReportOneInfo) &&  $getWelcomeUserReportOneInfo->complete_date != '')
        {
            $infos['complete_date'] =  $getWelcomeUserReportOneInfo->complete_date;
        }

        return JsonBuilder::Success($infos, '报到确认');
    }

    /**
     * Func 报到单
     * @param Request $request
     * @return Json
     */
    public function report_info(IndexRequest $request)
    {
        $user = $request->user();

        $user_id = $user->id;

        $campus_id = $user->gradeUser->campus_id;

        if (!intval($campus_id) || !intval($user_id)) {
            return JsonBuilder::Error('参数错误');
        }


        // 获取报到单信息
        $WelcomeUserReportDao = new WelcomeUserReportDao();

        $data = $WelcomeUserReportDao->getUserReportOrUserProfilesInfo($user_id);

        // TODO.....下面费用未按照实际计算
        // 基本信息
        $infos = [
            // 基本信息
            ['title'=>'基本信息','dataList' => array(
                array('title'=>'姓名','value'=>$data['user_name']),
                array('title'=>'学号','value'=>$data['student_id']),
                array('title'=>'身份证','value'=>$data['id_number']),
                array('title'=>'学院','value'=>$data['institute_name']),
                array('title'=>'专业','value'=>$data['major_name']),
                array('title'=>'班级','value'=>$data['class_name']),
            )],
            // 费用信息
            ['title'=>'费用信息','dataList' => array(
                array('title'=>'学费','notice'=>'已缴费','value'=>'5000.00'),
                array('title'=>'书费','notice'=>'已缴费','value'=>'1000.00'),
                array('title'=>'住宿费','notice'=>'已缴费','value'=>'800.00'),
                array('title'=>'宿舍','notice'=>'','value'=>'1号楼3层309'),
                array('title'=>'生活用品','notice'=>'未领取','value'=>'125.00'),
                array('title'=>'军训用品','notice'=>'未领取','value'=>'100.00'),
            )],
            // 提供资料
            ['title'=>'提供资料','dataList' => array(
                array('title'=>'报到函','notice'=>'','value'=>'已提交'),
                array('title'=>'党团文件','notice'=>'','value'=>'已提交'),
                array('title'=>'录取通知书','notice'=>'','value'=>'已提交'),
            )]
        ];

        return JsonBuilder::Success($infos, '报到单信息');
    }

    /**
     * Func 迎新指南
     * @param Request $request
     * @return page
     */
    public function page_info(\Illuminate\Http\Request $request)
    {
        // 校区
        $campus_id = $request->input('campus_id', 0);

        // 类型(1:报到流程内容,2:迎新指南内容)
        $typeid = $request->input('typeid', 1);

        // 内容
        $content = null;
        if($campus_id && $typeid)
        {
            // 获取字段
            $fieldArr = [1=>'config_content1 as content',2=>'config_content2 as content'];

            $WelcomeConfigDao = new WelcomeConfigDao();

            $getWelcomeConfigOneInfo = $WelcomeConfigDao->getWelcomeConfigOneInfo( $campus_id , $fieldArr[$typeid] );

            $content = $getWelcomeConfigOneInfo->content;
        }

        $this->dataForView['content'] = $content;

        return view('welcome.page_view', $this->dataForView);

    }

}
