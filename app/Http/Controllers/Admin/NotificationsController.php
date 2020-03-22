<?php
namespace App\Http\Controllers\Admin;

use App\Dao\Misc\SystemNotificationDao;

use App\Dao\Schools\SchoolDao;
use App\Dao\Version\VersionDao;
use App\Models\Misc\SystemNotification;
use App\Models\School;
use App\Http\Controllers\Controller;
use App\Models\Version\Version;
use App\Utils\FlashMessageBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Uuid;

class NotificationsController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }

    /**
     * Func 消息列表
     * @param Request $request
     * @return \resources\views\admin\notification\list.blade.php
     */
    public function list(Request $request)
    {
      $to = (Int)$request->input('to', 0);
      $page = (Int)$request->input('page', 1);
      $keywords = (String)$request->input('keywords', '');

      $school = session('school');
      if (empty($school)) {
        FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '请选择学校');
        return redirect('/home');
      }

      // 检索参数
      $param['keywords'] = $keywords;
      $param['school_id'] = $school['id'];
      $param['to'] = $to?[$to]:[0,-1,-2];
      $systemNotificationDao = new SystemNotificationDao();
      $dataList = $systemNotificationDao->getNotificationList($param,$page);

      $this->dataForView['dataList'] = $dataList;
      $this->dataForView['pageTitle'] = '消息列表';
      return view('admin.notifications.list', $this->dataForView);
    }

    /**
     * Func 消息添加
     * @param Request $request
     * @return \resources\views\admin\notification\add.blade.php
     */
    public function add(Request $request)
    {
      if ($request->isMethod('post'))
      {
        $info = $request->post('info');
        // 添加的参数
        $addData['uuid'] = Uuid::uuid4()->toString();
        $addData['school_id'] = session('school.id');
        $addData['sender'] = 0; // 发信人 ID, 0 表示系统自动发送
        $addData['to'] = (Int)$info['to']; // 收信人ID
        $addData['type'] = 0; // 目前没用
        $addData['priority'] = 0; // 目前没用
        $addData['title'] = $info['title']; // 标题
        $addData['content'] = $info['content']; // 内容
        $addData['next_move'] = ''; // 目前没用
        $addData['category'] = SystemNotification::COMMON_CATEGORY_MESSAGE; // 后台系统消息
        $addData['app_extra'] = json_encode(
          [
            'type' => 'web-view',
            'param1' => route('h5_apps.banner.notification_info', ['uuid' => $addData['uuid']]),
            'param2' => []
          ]
        );
        $systemNotificationDao = new SystemNotificationDao();
        $result = $systemNotificationDao->create($addData,[]);
        if($result) {
          FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'添加成功');
          return redirect()->route('admin.notifications.list')->withInput();
        } else {
          FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'添加失败,请稍后重试');
          return redirect()->route('admin.notifications.add')->withInput();
        }
      }

      $this->dataForView['redactor'] = true;
      $this->dataForView['js'] = [
        'admin.notifications.intro_js'
      ];
      $this->dataForView['pageTitle'] = '添加消息';
      return view('admin.notifications.add', $this->dataForView);
    }

    /**
     * Func 消息修改
     * @param Request $request
     * @return \resources\views\admin\notification\edit.blade.php
     */
    public function edit(Request $request)
    {
      $uuid = (String)$request->input('uuid');
      if (!$uuid) {
        FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
        return redirect()->route('admin.notifications.list');
      }

      // 获取数据
      $systemNotificationDao = new SystemNotificationDao();
      $dataOne = $systemNotificationDao->getNotificationOne(['uuid' => $uuid ]);
      if (empty($dataOne)) {
        FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '数据不存在');
        return redirect()->route('admin.notifications.list');
      }

      if ($request->isMethod('post'))
      {
        $info = $request->post('info');
        $saveData['to'] = (Int)$info['to']; // 收信人ID
        $saveData['title'] = $info['title']; // 标题
        $saveData['content'] = $info['content']; // 内容
        $result = SystemNotification::where('id',$dataOne->id)->update($saveData);
        if($result) {
          FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'修改成功');
          return redirect()->route('admin.notifications.list')->withInput();
        } else {
          FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'修改失败,请稍后重试');
          return redirect()->route('admin.notifications.add')->withInput();
        }
      }

      $this->dataForView['dataOne'] = $dataOne;
      $this->dataForView['redactor'] = true;
      $this->dataForView['js'] = [
        'admin.notifications.intro_js'
      ];
      $this->dataForView['pageTitle'] = '修改消息';
      return view('admin.notifications.edit', $this->dataForView);
    }

    /**
     * Func 消息删除
     * @param Request $request
     * @return \resources\views\admin\notification\list.blade.php
     */
    public function delete(Request $request)
    {
      $uuid = (String)$request->input('uuid');
      if (!$uuid) {
        FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER, '参数错误');
        return redirect()->route('admin.notifications.list');
      }

      SystemNotification::where(['uuid'=>$uuid])->delete();

      FlashMessageBuilder::Push($request, 'success', '删除成功');

      return redirect()->route('admin.notifications.list');
    }
}
