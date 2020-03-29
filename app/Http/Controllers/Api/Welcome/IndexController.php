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
use App\Models\Welcome\WelcomeConfigStep;

use App\Http\Requests\MyStandardRequest;
use App\Models\OA\InternalMessage;
use Illuminate\Http\Request;
use Psy\Util\Json;
use App\Utils\JsonBuilder;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Welcome\IndexRequest;
use App\Models\Notices\AppProposalImage;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Storage;

class IndexController extends Controller
{

  /**
   * 上传图片
   * @param IndexRequest $request
   * @return string
   */
  public function uploadFiles(IndexRequest $request)
  {
    $files = $request->file('file');
    $infos = [];
    if ($files) {
      $infos['name'] = $files->getClientOriginalName();
      $infos['type'] = $files->extension();
      $infos['size'] = getFileSize($files->getSize());
      $ext = $files->getClientOriginalExtension();
      $fileName = date('Y-m-d').'-'.rand(10000,99999).'.'.$ext;
      $uploadResult = Storage::disk('banner')->put($fileName, file_get_contents($files->getRealPath()));
      $infos[ 'path' ] = '/storage/banner/'.$fileName;
    }
    return JsonBuilder::Success($infos);
  }


  /**
   * Func 获取下载地址
   *
   * @param Request $request
   * @param['token'] 是  token
   *
   * @return Json
   */
  public function download(Request $request)
  {
    $sid = (Int)$request->input('sid', '');
    if (!$sid) return;
    $infos = DB::table('versions')->where('sid','=',$sid)->first();
    return response()->download(storage_path($infos->version_downurl));
  }
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

        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        if (!intval($school_id) || !intval($user_id)) {
            return JsonBuilder::Error('参数错误');
        }

        // 获取是否已完善信息
        $WelcomeUserReportDao = new  WelcomeUserReportDao();

        $getWelcomeUserReportOneInfoField = ['configid','flow_id','steps_1_date','steps_2_date','complete_date', 'status'];

        $getWelcomeUserReportOneInfo = $WelcomeUserReportDao->getWelcomeUserReportOneInfo($user_id,$getWelcomeUserReportOneInfoField);

        // 获取迎新配置信息
        $checkUserIsWelcomeInfo = $WelcomeUserReportDao->checkUserIsWelcomeInfo( $campus_id, $user_id);

        // 获取配置信息
        $infos = [];

        $WelcomeConfigDao = new WelcomeConfigDao();

        // 获取数据
        $infos['menu'] = [];
        $data = $WelcomeConfigDao->getWelcomeConfigStepListInfo($school_id);
        if (!empty($data)) {
            $data1 = [];
            foreach ($data as $key => $val) {
                if (isset($data1[$val['gname']])) {
                    array_push($data1[$val['gname']], $val);
                } else {
                    $data1[$val['gname']][] = $val;
                }
            }
            foreach ($data1 as $_k => $_v) {
                $data2[] = array('name' => $_k, 'data' => $_v);
            }
            $infos['menu'] = $data2;
        }
        if(is_array($infos['menu']) && count($infos['menu']) >  0 )
        {
            // 菜单只分二级，不能在三级了。
            foreach($infos['menu'] as $key=>&$val)
            {
                foreach($val['data'] as $k=>&$v)
                {
                    /*// 如果个人已经报到，按照个人报到信息展示
                    if(empty($getWelcomeUserReportOneInfo->configid))
                    {*/
                        // 个人信息(A)
                        if($v['letter'] == 'A')
                        {
                            $checkWelcomeUserReportOneInfo = $WelcomeUserReportDao->checkWelcomeUserReportOneInfo($getWelcomeUserReportOneInfo,'A');

                            $v['notice'] = $checkWelcomeUserReportOneInfo['notice'];
                            $v['status'] = $checkWelcomeUserReportOneInfo['status'];// 0：弹出message，1：表示已完成，2：为未完善信息
                            $v['message'] = $checkWelcomeUserReportOneInfo['message'];
                        }

                        // 报到扫码(B)
                        if($v['letter'] == 'B')
                        {
                            $checkWelcomeUserReportOneInfo = $WelcomeUserReportDao->checkWelcomeUserReportOneInfo($getWelcomeUserReportOneInfo,'B');

                            $v['notice'] = $checkWelcomeUserReportOneInfo['notice'];
                            $v['status'] = $checkWelcomeUserReportOneInfo['status'];
                            $v['message'] = $checkWelcomeUserReportOneInfo['message'];
                        }

                        // 报到单(C)
                        if($v['letter'] == 'C')
                        {
                            $checkWelcomeUserReportOneInfo = $WelcomeUserReportDao->checkWelcomeUserReportOneInfo($getWelcomeUserReportOneInfo,'C');

                            $v['notice'] = $checkWelcomeUserReportOneInfo['notice'];
                            $v['status'] = $checkWelcomeUserReportOneInfo['status'];
                            $v['message'] = $checkWelcomeUserReportOneInfo['message'];
                        }

                    /*} else {
                        // 学校全局配置验收
                        $v['notice'] = $checkUserIsWelcomeInfo['notice']; // 提示信息
                        $v['status'] = $checkUserIsWelcomeInfo['status']; // 0：弹出message
                        $v['message'] = $checkUserIsWelcomeInfo['message'];
                    }*/
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

        $school_id = $user->gradeUser->school_id;
        $campus_id = $user->gradeUser->campus_id;

        if (!intval($campus_id) || !intval($user_id)) {
            return JsonBuilder::Error('参数错误');
        }

        // 获取用户基础信息
        $WelcomeUserReportDao = new WelcomeUserReportDao();

        $infos = $WelcomeUserReportDao->getUserReportOrUserProfilesInfo($user_id);


        $WelcomeConfigDao = new WelcomeConfigDao();

        $getWelcomeConfigOneInfo = $WelcomeConfigDao->getWelcomeConfigOneInfo( $school_id , $fieldArr = ['config_content1'] );

        // 报到流程
        $infos['config_content1'] = '';

        if(!empty($getWelcomeConfigOneInfo->config_content1))
        {
            $infos['config_content1'] = $getWelcomeConfigOneInfo->config_content1;
        }

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

        $WelcomeUserReportDao = new WelcomeUserReportDao();

        // 验证是否有权限操作迎新
        $checkUserIsWelcomeInfo = $WelcomeUserReportDao->checkUserIsWelcomeInfo( $school_id, $user_id);
        if($checkUserIsWelcomeInfo['status'] != true)
        {
            return JsonBuilder::Error($checkUserIsWelcomeInfo['message']);
        }

        // 添加或更新数据
        $data['flow_id'] = 1;
        $data['user_id'] = $user_id;
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['school_id'] = $school_id;
        $data['campus_id'] = $campus_id;
        $data['user_name'] = $steps_1_Arr['user_name'];
        $data['user_number'] = $steps_1_Arr['id_number'];
        $data['steps_1_str'] = json_encode($steps_1_Arr);
        $data['steps_1_date'] = date('Y-m-d H:i:s');
        $data['steps_2_date'] = null;
        $data['complete_date'] = null;

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

        $WelcomeUserReportDao = new WelcomeUserReportDao();

        // 验证是否有权限操作迎新
        $checkUserIsWelcomeInfo = $WelcomeUserReportDao->checkUserIsWelcomeInfo( $campus_id, $user_id);
        if($checkUserIsWelcomeInfo['status'] != true)
        {
            return JsonBuilder::Error($checkUserIsWelcomeInfo['message']);
        }

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

        // 获取是否必传照片信息
        $getWelcomeConfigStepOneInfo = $WelcomeUserReportDao->getWelcomeConfigStepOneInfo($school_id, 1);
        if (!empty($getWelcomeConfigStepOneInfo)) {
          // 验证照片是否比传
          $checkArr = json_decode($getWelcomeConfigStepOneInfo['steps_json'], true);
          if(!empty($checkArr)) {
            if (empty($path['photo']) && $checkArr['photo']['status'] == 1) {
              return JsonBuilder::Error('请上传照片');
            }
            if (empty($path['card_front']) && $checkArr['card_front']['status'] == 1) {
              return JsonBuilder::Error('请上传身份证正面照片');
            }
            if (empty($path['card_reverse']) && $checkArr['card_front']['status'] == 1) {
              return JsonBuilder::Error('请上传身份证反面照片');
            }
          }
        }

        // 更新数据
        $picsArr = [
            'photo' => !empty($path['photo'])?$path['photo']:"", // 照片
            'card_front' => !empty($path['card_front'])?$path['card_front']:"", // 身份证正面照片
            'card_reverse' => !empty($path['card_reverse'])?$path['card_reverse']:"" // 身份证反面照片
        ];

        // 添加或更新数据
        $data['steps_2_str'] = json_encode($picsArr);
        $data['steps_2_date'] = date('Y-m-d H:i:s');
        $data['complete_date'] = null;

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
            $infos['complete_date'] =  date('Y-m-d H:i',strtotime($getWelcomeUserReportOneInfo->complete_date));
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
        $dataList1 = $dataList2 = [];
        if (!empty($data)) {
            // 缴费信息
            $result1 = $WelcomeUserReportDao->getWelcomeProjectListInfo(1);
            if (!empty($result1)) {
                foreach ($result1 as $key => $val) {
                    $result3 = $WelcomeUserReportDao->getWelcomeUserReportsProjectsOneInfo($user_id, $val['typeid']);
                    $dataList1[] = array(
                        'title' => $val['type_name'],
                        'notice' => isset($result3['projectid']) ? '已缴费' : '未交费',
                        'value' => isset($result3['pay_price']) ? $result3['pay_price'] : '',
                    );
                }
            }
            // 报到资料
            $result2 = $WelcomeUserReportDao->getWelcomeProjectListInfo(2);
            if (!empty($result2)) {
                foreach ($result2 as $key => $val) {
                    $result3 = $WelcomeUserReportDao->getWelcomeUserReportsProjectsOneInfo($user_id, $val['typeid']);
                    $dataList2[] = array(
                        'title' => $val['type_name'],
                        'notice' => '',
                        'value' => isset($result3['projectid']) ? '已提交' : '未提交',
                    );
                }
            }
        }

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
            // array('title'=>'宿舍','notice'=>'','value'=>'1号楼3层309'),
            // array('title'=>'生活用品','notice'=>'未领取','value'=>'125.00'),
            // array('title'=>'军训用品','notice'=>'未领取','value'=>'100.00'),
            ['title'=>'费用信息','dataList' => $dataList1],
            // 提供资料
            ['title'=>'提供资料','dataList' => $dataList2]
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
