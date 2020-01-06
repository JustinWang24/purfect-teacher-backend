<?php


namespace App\Dao\Importer;


use App\Dao\BuildFillableData;
use App\Models\Importer\ImoprtLog;
use App\Models\Importer\ImoprtTask;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;
use function Complex\asec;

class ImporterDao
{
    use BuildFillableData;
    public function __construct()
    {

    }

    public function create($data)
    {
        if (!isset($data['id']) || empty($data['id'])) {
            unset($data['id']);
        }
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new ImoprtTask(), $data);
            $importTask = ImoprtTask::create($fillableData);
            if ($importTask) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($importTask);
            } else {
                DB::rollBack();
                $messageBag->setMessage('保存导入任务失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    public function update($data)
    {
        $id = $data['id'];
        unset($data['id']);
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new ImoprtTask(), $data);
            $importTask = ImoprtTask::where('id', $id)->update($fillableData);
            if ($importTask) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData(ImoprtTask::find($id));
            } else {
                DB::rollBack();
                $messageBag->setMessage('更新导入任务失败, 请联系管理员');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    public function getTaskById($id, $field="*")
    {
        return ImoprtTask::where('id', $id)->select($field)->first();
    }
    public function getTasks($schoolId=null)
    {
        if(empty($schoolId)) {
            return ImoprtTask::all();
        } else {
            return ImoprtTask::where('school_id', $schoolId)->get();
        }
    }

    public function writeLog($data)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new ImoprtLog(), $data);
            $importLog = ImoprtLog::create($fillableData);
            if ($importLog) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
                $messageBag->setData($importLog);
            } else {
                DB::rollBack();
                $messageBag->setMessage('日志写入失败');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    public function updateLog($data)
    {
        $messageBag = new MessageBag(JsonBuilder::CODE_ERROR);
        DB::beginTransaction();
        try {
            $fillableData = $this->getFillableData(new ImoprtLog(), $data);
            $importLog = ImoprtLog::where('only_flag',md5($data['only_flag']))->update($fillableData);
            if ($importLog) {
                DB::commit();
                $messageBag->setCode(JsonBuilder::CODE_SUCCESS);
            } else {
                DB::rollBack();
                $messageBag->setMessage('日志更新失败');
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $messageBag->setMessage($exception->getMessage());
        }
        return $messageBag;
    }

    public function getLog($onlyFlag) {
        return ImoprtLog::where('only_flag',$onlyFlag)->first();
    }

    public function result($id, $schoolId=null)
    {
        if (empty($schoolId)) {
            return ImoprtLog::where('task_id', $id)->where('task_status',ImoprtLog::ADMIN_FAIL_STATUS)->get();
        } else {
            return ImoprtLog::where('task_id', $id)->where('school_id', $schoolId)->where('task_status',ImoprtLog::FAIL_STATUS)->get();
        }
    }

    /**
     * 按顺序取出未处理的导入需求，每次只取一条处理，定时任务每小时执行一次，每天导入最多24条任务
     * @return mixed
     */
    public function getTasksForNewPlan()
    {
        return ImoprtTask::where('status', 1)->where('school_id', '>', 0)->orderBy('id', 'asc')->first();

    }

}
