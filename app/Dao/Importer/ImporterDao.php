<?php


namespace App\Dao\Importer;


use App\Dao\BuildFillableData;
use App\Models\Importer\ImoprtLog;
use App\Models\Importer\ImoprtTask;
use App\Utils\JsonBuilder;
use App\Utils\ReturnData\MessageBag;
use Illuminate\Support\Facades\DB;

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
    public function getTasks()
    {
        return ImoprtTask::all();
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

    public function result($id)
    {
        return ImoprtLog::where('task_id', $id)->where('task_status',2)->get();
    }
}
