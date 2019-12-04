<?php


namespace App\Http\Controllers\Admin;


use App\BusinessLogic\ImportExcel\Factory;
use App\Dao\Importer\ImporterDao;
use App\Dao\Users\UserDao;
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


    public function handle(Request $request, $id)
    {
        $user = $request->user();
/*        $dao = new ImporterDao();
        $taskObj = $dao->getTaskById($id);
        $taskConfig = json_decode($taskObj->config, true);
        $taskConfig['importerName'] =  'App\BusinessLogic\ImportExcel\Impl\\'.$taskConfig['importerName'].'Importer';
        $taskConfig['file_path']    =  $taskObj->file_path;
        $taskConfig['task_id']      =  $id;
        $obj = Factory::createAdapter($taskConfig);
        $obj->loadExcelFile();
        //检测配置文件

        //获取学校对象
        $schoolObj = $obj->getSchoolId($user);
        echo '学校《'.$schoolObj->name.'》资料获取成功<br>';
        $config = $obj->getConfig();
        $data = $obj->getData();
        $sheetIndexArray = $obj->getSheetIndexArray();

        foreach($sheetIndexArray as $sheetIndex)
        {
            //获取当前sheet的配置
            $sheetConfig = $config['school']['sheet'][$sheetIndex];
            if (empty($sheetConfig)) {
                echo '配置为空，跳过第'.$sheetIndex.'个sheet<br>';
                continue;
            }

            $sheetData = $obj->getSheetData($sheetIndex);
            echo '获取到第'.$sheetIndex.'个sheet数据开始循环<br>';
            foreach ($sheetData as $key => $row)
            {
                if ($key<$sheetConfig['dataRow'])
                    continue;

                $rowData = $obj->getRowData($sheetIndex, $row);
                echo '获取到一行资料'.json_encode($row,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES).'<br>';
                //手机号不能为空
                if (empty($rowData['mobile'])) {
                    $obj->writeLog($row);
                    echo '手机号为空跳过<br>';
                    continue;
                }

                //判断是否有学院
                if (!isset($rowData['institute'])) {
                    $rowData['institute'] = '默认';
                }
                if (!isset($rowData['department'])) {
                    $rowData['department'] = '默认';
                }
                $institute = $obj->getInstitute($user, $rowData['institute'], $schoolObj->id);
                if ($institute) {
                    $department = $obj->getDepartment($user, $rowData['department'], $schoolObj->id, $institute);
                    if ($department) {
                        $major = $obj->getMajor($user, $rowData['major'], $schoolObj->id, $institute, $department);
                        if ($major) {
                            $rowData['grade'] = explode('-',$rowData['grade'])[0];
                            $rowData['year'] = substr($rowData['year'],0,4);
                            $grade = $obj->getGrade($user, $rowData['grade'], $schoolObj->id, $major, $rowData['year']);
                            if ($grade) {
                                $passwordInPlainText = substr($rowData['idNumber'],-4);
                                $importUser = $obj->getUser($rowData['mobile'], $rowData['userName'], $passwordInPlainText,$row);
                                if ($importUser) {
                                    $gradeUser = $obj->getGradeUser($importUser, $rowData,$schoolObj->id, $institute, $department, $major, $grade, $row);
                                }else{
                                    echo '班级用户《'.$rowData['userName'].'》创建失败跳过<br>';
                                }
                            }else{
                                echo '班级《'.$rowData['grade'].'》创建失败跳过<br>';
                            }
                        }else{
                            echo '专业《'.$rowData['major'].'》创建失败跳过<br>';
                        }
                    }else{
                        echo '系《'.$rowData['department'].'》创建失败跳过<br>';
                    }
                }else{
                    echo '学院《'.$rowData['institute'].'》创建失败跳过<br>';
                }
            }
        }*/


        //Artisan::call('命令名称');
        Artisan::call('importer', [
            'configId' => $id,
            'userId' => $user->id,
        ])->runInBackground();
    }

}
