<?php
namespace App\Http\Controllers\Admin;
use App\Dao\Version\VersionDao;
use App\Models\School;
use App\Http\Controllers\Controller;
use App\Models\Version\Version;
use App\Utils\FlashMessageBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VersionController extends Controller
{
    //类型(1:安卓,2:IOS)
    private static $typeidArr = [1 => '安卓', 2 => 'IOS'];

    //安卓更新(1:强制更新,2:不强制更新)
    private static $isupdateArr = [1 => '强制更新', 2 => '不强制更新'];

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 管理问卷调查表的 action
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request){
        $dao = new VersionDao();
        $data1_1 = $dao->getVersionOneInfo([['user_apptype', '=', 1], ['typeid', '=', 1]]);
        $data1_2 = $dao->getVersionOneInfo([['user_apptype', '=', 1], ['typeid', '=', 2]]);
        $data2_1 = $dao->getVersionOneInfo([['user_apptype', '=', 2], ['typeid', '=', 1]]);
        $data2_2 = $dao->getVersionOneInfo([['user_apptype', '=', 2], ['typeid', '=', 2]]);
        $data2_1 = $dao->getVersionOneInfo([['user_apptype', '=', 3], ['typeid', '=', 1]]);
        $data2_2 = $dao->getVersionOneInfo([['user_apptype', '=', 3], ['typeid', '=', 2]]);
        $versions = collect([$data1_1, $data1_2, $data2_1, $data2_2]);

        $this->dataForView['versions'] = $versions;
        $this->dataForView['typeidArr'] = self::$typeidArr;
        $this->dataForView['isupdateArr'] = self::$isupdateArr;
        $this->dataForView['pageTitle'] = 'APP版本列表';
        return view('admin.versions.list', $this->dataForView);
    }

    /**
     * 加载添加问卷的表单
     */
    public function add()
    {
        $this->dataForView['pageTitle'] = '添加版本';
        $this->dataForView['typeidArr'] = self::$typeidArr;
        $this->dataForView['isupdateArr'] = self::$isupdateArr;
        $this->dataForView['version'] = new Version();
        return view('admin.versions.add', $this->dataForView);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(Request $request){
        $dao = new VersionDao();
        $version = $dao->getVersionById($request->id);
        $this->dataForView['version'] = $version;
        $this->dataForView['pageTitle'] = '修改APP软件版本';
        return view('admin.versions.edit', $this->dataForView);
    }

    /**
     * 保存
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request){

        $versionData = $request->post('version');

        $dao = new VersionDao();

        // 查询是否存在
        $condition[] = ['typeid', '=', $versionData['typeid']];
        $condition[] = ['version_id', '=', $versionData['version_id']];
        $condition[] = ['user_apptype', '=', $versionData['user_apptype']];
        $getVersionOneInfo = $dao->getVersionOneInfo($condition);

        // 判断是否存在
        if(isset($getVersionOneInfo->sid)){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'版本号已存在');
            return redirect()->back()->withInput();
        }

        // 获取学校信息
        $schoolInfo = School::where('id', '>', 0)->get();
        $schoolArr = !empty($schoolInfo) ? $schoolInfo->toArray() : [];
        $schoolStr = implode(array_column($schoolArr,'id'),',');
        if(empty($schoolArr)){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'当前无学校');
            return redirect()->back()->withInput();
        }

        // 安卓
        if($versionData['typeid'] == 1)
        {
            $fileCharater = $request->file('source');
            if (!empty($fileCharater) && $fileCharater->isValid())
            {
                //获取文件的扩展名
                $ext = $fileCharater->getClientOriginalExtension();

                if ('apk' != $ext)
                {
                    FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'资源文件类型错误');
                    return redirect()->back()->withInput();
                }

                $fileName = date('Y-m-d').'-'.rand(100,999).'.'.$ext;
                //获取文件的绝对路径
                $path = $fileCharater->getRealPath();
                //存储文件。disk里面的public。总的来说，就是调用disk模块里的public配置
                $uploadResult = Storage::disk('apk')->put($fileName, file_get_contents($path));
                if ($uploadResult)
                {
                    $versionData[ 'version_downurl' ] = '/app/apk/'.$fileName;
                }
            }
        }

        // 失效时间
        $versionData['schoolids'] = $schoolStr;
        $versionData['vserion_invalidtime'] = strtotime($versionData['vserion_invalidtime']);

        $result = $dao->create($versionData);
        if($result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,'操作成功');
            return redirect()->route('admin.versions.list');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'操作失败,请稍后重试');
            return redirect()->back()->withInput();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public  function delete(Request $request)
    {
        $dao = new VersionDao();
        $deleted = $dao->delete($request->sid);
        if($deleted){
            FlashMessageBuilder::Push($request, 'success','删除成功');
        }
        else{
            FlashMessageBuilder::Push($request, 'success','删除失败');
        }
        return redirect()->route('admin.versions.list');
    }



    /**
     * Func 版本详情
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function detail(Request $request)
    {
        $typeid = $request->get('typeid');
        $user_apptype = $request->get('user_apptype');

        $dao = new VersionDao();
        $versions = $dao->getVersionListInfo(
            [['user_apptype', '=', $user_apptype], ['typeid', '=', $typeid]]
        );

        $this->dataForView['versions'] = $versions;
        $this->dataForView['typeidArr'] = self::$typeidArr;
        $this->dataForView['isupdateArr'] = self::$isupdateArr;

        $this->dataForView['pageTitle'] = 'APP历史版本';

        return view('admin.versions.detail', $this->dataForView);
    }
}
