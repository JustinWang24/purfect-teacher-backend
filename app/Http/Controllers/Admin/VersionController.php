<?php
namespace App\Http\Controllers\Admin;
use App\Dao\Version\VersionDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\Version\Version;
use App\Utils\FlashMessageBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VersionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 管理问卷调查表的 action
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(MyStandardRequest $request){

        $dao = new VersionDao();
        $versions = $dao->getVersions();
        $this->dataForView['versions'] = $versions;
        return view('admin.versions.list', $this->dataForView);

    }

    /**
     * 加载添加问卷的表单
     */
    public function add(){
        $this->dataForView['version'] = new Version();
        return view('admin.versions.add', $this->dataForView);
    }
    /**
     * 加载添加问卷的表单
     */
    public function edit(Request $request){
        $dao = new VersionDao();
        $version = $dao->getVersionById($request->id);
        $this->dataForView['version'] = $version;
        return view('admin.versions.edit', $this->dataForView);
    }


    public function update(Request $request){
        $versionData = $request->get('version');
        $fileCharater = $request->file('source');

        if (!empty($fileCharater) && $fileCharater->isValid()) {
            //获取文件的扩展名
            $ext = $fileCharater->getClientOriginalExtension();

            if ('apk' != $ext)
            {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'资源文件类型错误');
                return redirect()->back()->withInput();
//                return redirect()->route('admin.versions.edit',['id'=>$versionData['id']]);
            }

            $fileName = $fileCharater->getClientOriginalName();
            //获取文件的绝对路径
            $path = $fileCharater->getRealPath();
            //存储文件。disk里面的public。总的来说，就是调用disk模块里的public配置
            $uploadResult = Storage::disk('apk')->put($fileName, file_get_contents($path));
            if ($uploadResult)
            {
                $fileConfig = config('filesystems.disks.apk');
                $versionData['download_url'] =$fileConfig['url'].'/'.$fileName;
                $versionData['local_path'] =$fileName;
            }

        }
        $dao = new VersionDao();

        if(isset($versionData['id'])){
            $result = $dao->update($versionData);
        }
        else{
            $result = $dao->create($versionData);
        }

        if($result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$versionData['name'].'版本号保存成功');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'无法保存版本号'.$versionData['name']);
        }
        return redirect()->route('admin.versions.list');
    }

    public  function delete(Request $request)
    {
        $dao = new VersionDao();
        $version = $dao->delete($request->id);
        return redirect()->route('admin.versions.list');
    }
}
