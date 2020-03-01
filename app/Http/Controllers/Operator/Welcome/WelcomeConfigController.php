<?php
namespace App\Http\Controllers\Operator\Welcome;

use App\Dao\Schools\SchoolDao;
use App\Dao\Welcome\Backstage\WelcomeStepDao;
use App\Dao\Welcome\Backstage\WelcomeConfigDao;
use App\Dao\Welcome\Backstage\WelcomeUserReportDao;
use App\Dao\Welcome\Backstage\WelcomeConfigStepDao;

use App\Models\Welcome\WelcomeConfigStep;


use App\Dao\Contents\AlbumDao;
use App\Dao\Schools\NewsDao;
use App\Dao\Schools\NewsSectionDao;
use App\Models\School;
use App\Models\Schools\News;
use App\Utils\FlashMessageBuilder;
use App\Utils\JsonBuilder;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class WelcomeConfigController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    private static $stepArr = array(
        'save_user_info' => 1, // 个人信息保存
        'save_report_confirm_info' => 2, // 报到确认
        'save_report_bill_info' => 3, // 报到单
    );

    /**
     * Func 配置首页
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $uuid = $request->get('uuid');

        $schoolObj = new SchoolDao();
        $school = $schoolObj->getSchoolByIdOrUuid($uuid);

        // 获取基础配置信息
        $welcomeConfigObj = new WelcomeConfigDao();
        $getWelcomeConfigOneInfo = $welcomeConfigObj->getWelcomeConfigOneInfo($school->id);

        $this->dataForView['type'] = $request->get('type');
        $dao = new NewsDao();
        $this->dataForView['newsList'] = $dao->paginateByType(
            $request->get('type'),
            $request->session()->get('school.id')
        );

        return view('welcome_manager.welcomeConfig.index', $this->dataForView);
    }

    /**
     * Func 迎新基础信息配置
     * @param Request $request
     * @return string
     */
    public function save_base_info(Request $request)
    {
        // 获取参数
        $school_id = $request->input('data.school_id', 0);
        $config_sdata = $request->input('data.config_sdata', '');
        $config_edate = $request->input('data.config_edate', '');
        $config_content2 = $request->input('data.config_content2', '');
        if (empty($school_id)) {
            return JsonBuilder::Error('参数错误');
        }
        if (!strtotime($config_sdata)) {
            return JsonBuilder::Error('开始时间格式错误');
        }
        if (!strtotime($config_edate)) {
            return JsonBuilder::Error('结束时间格式错误');
        }
        if (strtotime($config_sdata) > strtotime($config_edate)) {
            return JsonBuilder::Error('开始时间不能大于结束时间');
        }
        if (!trim($config_content2)) {
            return JsonBuilder::Error('请填写迎新指南');
        }

        $welcomeConfigObj = new WelcomeConfigDao();
        $getWelcomeConfigOneInfo = $welcomeConfigObj->getWelcomeConfigOneInfo($school_id);
        if(empty($getWelcomeConfigOneInfo))
        {
            // 添加数据
            $addData['campus_id'] = 1;
            $addData['school_id'] = $school_id;
            $addData['config_sdata'] = $config_sdata;
            $addData['config_edate'] = $config_edate;
            $addData['config_content2'] = $config_content2;

            if ($welcomeConfigObj->addWelcomeConfigInfo($addData)) {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        }else {
            // 修改信息
            $saveData['config_sdata'] = $config_sdata;
            $saveData['config_edate'] = $config_edate;
            $saveData['config_content2'] = $config_content2;
            if ($welcomeConfigObj->editWelcomeConfigInfo($saveData,$getWelcomeConfigOneInfo['configid'])) {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        }
    }

    /**
     * Func 保存个人信息
     * @param Request $request
     * @return string
     */
    public function save_user_info(Request $request)
    {
        // 获取参数
        $school_id = $request->input('data.school_id', 0);
        $photo = $request->input('data.photo', 0);
        $card_front = $request->input('data.card_front', 0);
        $config_content1 = $request->input('data.config_content1', '');
        if (empty($school_id)) {
            return JsonBuilder::Error('参数错误');
        }
        if($photo == '') {
            return JsonBuilder::Error('请选择是否上传照片');
        }
        if($card_front == '') {
            return JsonBuilder::Error('请选择是否上传身份证');
        }
        if(trim($config_content1) == '') {
            return JsonBuilder::Error('请填写注意事项');
        }

        // 配置数据
        $steps_jsonArr = array(
            'photo' => array('status' => $photo),
            'card_front' => array('status' => $card_front),
            'card_reverse' => array('status'=>$card_front),
        );

        $welcomeConfigStepObj = new WelcomeConfigStepDao();
        $getWelcomeConfigStepOneInfo = $welcomeConfigStepObj->getWelcomeConfigStepOneInfo($school_id, self::$stepArr['save_user_info']);
        if(empty($getWelcomeConfigStepOneInfo))
        {
            // 添加数据
            $addData['school_id'] = $school_id;
            $addData['campus_id'] = 1;
            $addData['steps_id'] = self::$stepArr['save_user_info']; // 流程
            $addData['steps_json'] = json_encode($steps_jsonArr);
            if ($welcomeConfigStepObj->addWelcomeConfigStepInfo($addData)) {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        }else {
            // 修改信息
            $saveData['steps_id'] = self::$stepArr['save_user_info']; // 流程
            $saveData['steps_json'] = json_encode($steps_jsonArr);
            if ($welcomeConfigStepObj->editWelcomeConfigStepInfo($saveData,$getWelcomeConfigStepOneInfo['flowid'])) {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        }
    }

    /**
     * Func 报到确认
     * @param Request $request
     * @return string
     */
    public function save_report_confirm_info(Request $request)
    {
        // 获取参数
        $school_id = $request->input('data.school_id', 0);
        if (empty($school_id)) {
            return JsonBuilder::Error('参数错误');
        }

        $welcomeConfigStepObj = new WelcomeConfigStepDao();
        $getWelcomeConfigStepOneInfo = $welcomeConfigStepObj->getWelcomeConfigStepOneInfo(
            $school_id, self::$stepArr['save_report_confirm_info']
        );
        if(empty($getWelcomeConfigStepOneInfo))
        {
            // 添加数据
            $addData['campus_id'] = 1;
            $addData['school_id'] = $school_id;
            $addData['steps_id'] = self::$stepArr['save_report_confirm_info']; // 流程
            $addData['steps_json'] = json_encode(array());
            if ($welcomeConfigStepObj->addWelcomeConfigStepInfo($addData)) {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        }else {
            // 修改信息
            $saveData['steps_json'] = json_encode(array());
            if ($welcomeConfigStepObj->editWelcomeConfigStepInfo($saveData,$getWelcomeConfigStepOneInfo['flowid'])) {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        }
    }

    /**
     * Func 报到单
     * @param Request $request
     * @return string
     */
    public function save_report_bill_info(Request $request)
    {
        // 获取参数
        $school_id = $request->input('data.school_id', 0);
        if (empty($school_id)) {
            return JsonBuilder::Error('参数错误');
        }


        $welcomeConfigStepObj = new WelcomeConfigStepDao();
        $getWelcomeConfigStepOneInfo = $welcomeConfigStepObj->getWelcomeConfigStepOneInfo(
            $school_id, self::$stepArr['save_report_bill_info']
        );
        if(empty($getWelcomeConfigStepOneInfo))
        {
            // 添加数据
            $addData['campus_id'] = 1;
            $addData['school_id'] = $school_id;
            $addData['steps_id'] = self::$stepArr['save_report_bill_info']; // 流程
            $addData['steps_json'] = json_encode(array());
            if ($welcomeConfigStepObj->addWelcomeConfigStepInfo($addData)) {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        }else {
            // 修改信息
            $saveData['steps_json'] = json_encode(array());
            if ($welcomeConfigStepObj->editWelcomeConfigStepInfo($saveData,$getWelcomeConfigStepOneInfo['flowid'])) {
                return JsonBuilder::Success('操作成功');
            } else {
                return JsonBuilder::Error('操作失败,请稍后重试');
            }
        }
    }

    /**
     * Func 获取迎新流程
     * @param Request $request
     * @return string
     */
    public function get_report_list_info(Request $request)
    {
        $school_id = $request->input('data.school_id', 0);
        $infos = [];
        if ($school_id) {
            // 获取迎新基础配置
            $welcomeConfigObj = new WelcomeConfigDao();
            $infos['baseConfigInfo'] = $welcomeConfigObj->getWelcomeConfigOneInfo($school_id);

            // 获取迎新个人信息配置
            $welcomeConfigStepObj = new WelcomeConfigStepDao();
            $infos['userConfigInfo'] = $welcomeConfigStepObj->getWelcomeConfigStepOneInfo($school_id, 1);
            if(!empty($infos['userConfigInfo']))
            {
                $infos['userConfigInfo']['steps_json_arr'] = json_decode($infos['userConfigInfo']['steps_json'],true);
            }

            // 获取流程数据
            $data = $welcomeConfigStepObj->getWelcomeConfigStepListInfo($school_id);
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
                    $data2[] = array('title' => $_k, 'dataList' => $_v);
                }
                // 获取数据
                $infos['dataList1'] = $data;
                $infos['dataList2'] = $data2;
            }
        }
        return JsonBuilder::Success($infos, '操作成功');
    }

    /**
     * Func 删除单个流程
     * @param Request $request
     * @return string
     */
    public function delete_report_info(Request $request)
    {
        // 获取参数
        $flowid = $request->input('data.flowid', 0);
        if (!intval($flowid)) {
            return JsonBuilder::Error('参数错误');
        }
        $welcomeConfigStepObj = new WelcomeConfigStepDao();
        if ($welcomeConfigStepObj->deleteWelcomeConfigStepInfo($flowid))
        {
            return JsonBuilder::Success('操作成功');
        } else {
            return JsonBuilder::Error('操作失败,请稍后重试');
        }
    }

    /**
     * Func 流程向上
     * @param Request $request
     * @return string
     */
    public function up_report_info(Request $request)
    {
        // 获取参数
        $sort = $request->input('data.sort', 0); // 排序
        $flowid = $request->input('data.flowid', 0); // 流程id
        $school_id = $request->input('data.school_id', 0); // 学校id
        if (!intval($sort) || !intval($flowid) || !intval($school_id)) {
            return JsonBuilder::Error('参数错误');
        }

        // 检索条件
        $condition[] = ['school_id', '=', (Int)$school_id];
        $condition[] = ['sort', '<', (Int)$sort];

        $data = WelcomeConfigStep::where($condition)->first(['*']);
        $data = !empty($data) ? $data->toArray() : [];
        if (empty($data)) {
            return JsonBuilder::Error('已经是第一个了');
        }

        WelcomeConfigStep::where('flowid', '=', $data['flowid'])->update(['sort' => $sort]);
        WelcomeConfigStep::where('flowid', '=', $flowid)->update(['sort' => $data['sort']]);

        return JsonBuilder::Success('操作成功');
    }

    /**
     * Func 流程向下
     * @param Request $request
     * @return string
     */
    public function down_report_info(Request $request)
    {
        // 获取参数
        $sort = $request->input('data.sort', 0); // 排序
        $flowid = $request->input('data.flowid', 0); // 流程id
        $school_id = $request->input('data.school_id', 0); // 学校id
        if (!intval($sort) || !intval($flowid) || !intval($school_id)) {
            return JsonBuilder::Error('参数错误');
        }

        // 检索条件
        $condition[] = ['school_id', '=', (Int)$school_id];
        $condition[] = ['sort', '>', (Int)$sort];

        $data = WelcomeConfigStep::where($condition)->first(['*']);
        $data = !empty($data) ? $data->toArray() : [];
        if (empty($data)) {
            return JsonBuilder::Error('已经是最后一个了');
        }

        WelcomeConfigStep::where('flowid', '=', $data['flowid'])->update(['sort' => $sort]);
        WelcomeConfigStep::where('flowid', '=', $flowid)->update(['sort' => $data['sort']]);

        return JsonBuilder::Success('操作成功');
    }



















    //---------------------------下面是之前的--------------------------------

    /**
     * 加载校园简介内容管理界面
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function campus_intro(Request $request){
        /**
         * @var School $school
         */
        $school = (new SchoolDao())->getSchoolByIdOrUuid($request->get('uuid'));

        $campusIntro = $school->configuration->campus_intro;

        $this->dataForView['pageTitle'] = '校园简介';
        $this->dataForView['campusIntro'] = $campusIntro;
        $this->dataForView['school'] = $school;

        $this->dataForView['redactor'] = true;
        $this->dataForView['js'] = [
            'school_manager.news.campus_intro_js'
        ];

        return view('school_manager.news.campus_intro',$this->dataForView);
    }

    /**
     * 保存校园简介的方法
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function save_campus_intro(Request $request){
        $config = $request->get('config');
        (new SchoolDao())->saveSchoolIntro($config['school_id'], $config['campus_intro']);
        FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS, '保存成功');
        return redirect()->route('school_manager.contents.campus-intro',['uuid'=>$config['school_id']]);
    }

    /**
     * 校园相册管理
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function photo_album(Request $request){
        $school = (new SchoolDao())->getSchoolByIdOrUuid($request->get('uuid'));
        $dao = new AlbumDao();
        $album = $dao->getAllBySchool($school->id);
        $this->dataForView['pageTitle'] = '校园相册管理';
        $this->dataForView['album'] = $album;
        $this->dataForView['school'] = $school;
        return view('school_manager.news.album',$this->dataForView);
    }

    /**
     * 保存相册内容
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create_album(Request $request){
        $dao = new AlbumDao();
        $msgBag = $dao->create($request);
        if($msgBag->isSuccess()){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$msgBag->getMessage());
        }
        else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,$msgBag->getMessage());
        }
        return redirect()->route('school_manager.contents.photo-album',['uuid'=>$msgBag->getData()]);
    }

    /**
     * 删除相册内容
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete_album(Request $request){
        $dao = new AlbumDao();
        $album = $dao->getById($request->get('id'));
        if($album){
            $school = (new SchoolDao())->getSchoolById($album->school_id);
            $album->delete();
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'删除成功');
            return redirect()->route('school_manager.contents.photo-album',['uuid'=>$school->uuid]);
        }

    }

    public function management(Request $request){
        $this->dataForView['typeText'] = News::TypeText($request->get('type'));
        $this->dataForView['pageTitle'] = $this->dataForView['typeText'].'管理';
        $this->dataForView['type'] = $request->get('type');
        $dao = new NewsDao();
        $this->dataForView['newsList'] = $dao->paginateByType(
            $request->get('type'),
            $request->session()->get('school.id')
        );
        return view('school_manager.news.list',$this->dataForView);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function save(Request $request){
        $dao = new NewsDao();
        $schoolId = $request->get('school');
        $newsData = $request->get('news');
        $newsData['school_id'] = $schoolId;
        if(!isset($newsData['id']) || empty($newsData['id'])){
            $result = $dao->create($newsData);
            if($result->isSuccess()){
                return JsonBuilder::Success(['news'=>$result->getData()]);
            }
            else{
                return JsonBuilder::Error();
            }
        }else{
            unset($newsData['sections']);
            $dao->updateNewById($newsData['id'], $newsData);
            return JsonBuilder::Success();
        }
    }

    public function load(Request $request){
        $dao = new NewsDao();
        $newsId = $request->get('news_id');
        $news = $dao->getById($newsId);
        return JsonBuilder::Success(['news'=>$news]);
    }

    /**
     * 保存段落
     * @param Request $request
     * @return string
     */
    public function save_section(Request $request){
        $dao = new NewsSectionDao();
        $result = $dao->batchCreate($request->all());
        if($result->isSuccess()){
            $d = $result->getData();
            return $d ? JsonBuilder::Success(['id'=>$d]) : JsonBuilder::Success();
        }
        else{
            return JsonBuilder::Error($result->getMessage());
        }
    }

    public function delete(Request $request){
        $dao = new NewsDao();
        $dao->delete($request->get('news_id'));
        return JsonBuilder::Success();
    }

    public function publish(Request $request){
        $dao = new NewsDao();
        $dao->updateNewById($request->get('news_id'),['publish'=>true]);
        return JsonBuilder::Success();
    }

    /**
     * 删除段落
     * @param Request $request
     * @return string
     */
    public function delete_section(Request $request){
        $dao = new NewsSectionDao();
        $deleted = $dao->delete($request->get('section_id'));
        return $deleted ? JsonBuilder::Success(): JsonBuilder::Error();
    }

    /**
     * 段落上移
     * @param Request $request
     * @return string
     */
    public function move_up_section(Request $request){
        $dao = new NewsSectionDao();
        $result = $dao->moveUp($request->get('section_id'));
        return $result ? JsonBuilder::Success() : JsonBuilder::Error();
    }

    /**
     * 段落下移
     * @param Request $request
     * @return string
     */
    public function move_down_section(Request $request){
        $dao = new NewsSectionDao();
        return $dao->moveDown($request->get('section_id'))
            ? JsonBuilder::Success() : JsonBuilder::Error();
    }
}
