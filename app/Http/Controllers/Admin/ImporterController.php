<?php


namespace App\Http\Controllers\Admin;


use App\BusinessLogic\ImportExcel\Factory;
use App\Dao\Importer\ImporterDao;
use App\Http\Controllers\Controller;
use App\Models\Importer\ImoprtTask;
use App\User;
use App\Utils\FlashMessageBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class ImporterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function manager(Request $request)
    {
        $obj = Factory::createAdapter(
            ['importerName'=>'App\BusinessLogic\ImportExcel\Impl\LixianImporter',
             'file_path'   => '技术部加班表-11月-朱晨光.xlsx',
            ]);
        $obj->loadExcelFile();
        //dd($obj->data->toArray());

        //Artisan::call('命令名称');
        $dao = new ImporterDao();
        $tasks = $dao->getTasks();
        $this->dataForView['tasks'] = $tasks;
        return view('admin.importer.list', $this->dataForView);

    }

    public function add(){
        $this->dataForView['task'] = new ImoprtTask();
        return view('admin.importer.add', $this->dataForView);
    }

    public function edit(Request $request){
        $dao = new ImporterDao();
        $task = $dao->getTaskById($request->id);
        $task->congig = json_encode(json_decode($task->config,1),JSON_PRETTY_PRINT);
        $this->dataForView['task'] = $task;
        return view('admin.importer.edit', $this->dataForView);
    }

    public function update(Request $request)
    {
        $data = [];
        $dao = new ImporterDao();
        $data = $request->get('task');
        $data['config'] = json_encode(json_decode(strip_tags($data['config']),1));
        $data['title']  = strip_tags($data['title']);
        $user = $request->user();
        $data['manager_id'] = $user->id;


        $fileCharater = $request->file('source');

        if (!empty($fileCharater) && $fileCharater->isValid()) {
            //获取文件的扩展名
            $ext = $fileCharater->getClientOriginalExtension();

            if ('xlsx' != $ext)
            {
                FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'资源文件类型错误');
                return redirect()->back()->withInput();
            }

            $fileName = $fileCharater->getClientOriginalName();
            //获取文件的绝对路径
            $path = $fileCharater->getRealPath();
            //存储文件。disk里面的public。总的来说，就是调用disk模块里的public配置
            $uploadResult = Storage::disk('apk')->put($fileName, file_get_contents($path));
            if ($uploadResult)
            {
                $fileConfig = config('filesystems.disks.apk');
                $data['file_path'] =$fileName;
            }

        }

        if(isset($data['id'])){
            $result = $dao->update($data);
        }
        else{
            $data['status'] = 1;
            $result = $dao->create($data);
        }

        if($result){
            FlashMessageBuilder::Push($request, FlashMessageBuilder::SUCCESS,$data['title'].'任务保存成功');
        }else{
            FlashMessageBuilder::Push($request, FlashMessageBuilder::DANGER,'无法保存'.$data['title']);
        }
        return redirect()->route('admin.importer.manager');


    }


}
