<?php


namespace App\Dao\OfficialDocuments;

use App\Models\OfficialDocument\Progress;
use App\Models\OfficialDocument\ProgressSteps;
use App\Utils\Misc\ConfigurationTool;
use Illuminate\Support\Facades\DB;

class ProgressDao
{

    /**
     * 根据学校id 获取公文
     * @param $schoolId
     * @param $field
     * @return mixed
     */
    public function getProgressBySchoolId($schoolId, $field = '*')
    {
        return Progress::where('school_id', $schoolId)->select($field)->get();
    }

    public function getProgressBySchoolIdPaginate($schoolId, $field = '*')
    {
        return Progress::where('school_id', $schoolId)->select($field)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }

    /**
     * 添加公文流程
     * process_data key 是 第几步骤 val 是步骤id
     * @param $data
     * @return bool
     */
    public function createProcess($data)
    {
        $progressData = ['name' => $data['name'], 'school_id' => $data['school_id']];

        DB::beginTransaction();

        $progressResult = Progress::create($progressData);

        $presetStepData = [];
        foreach ($data['preset_step_id'] as $key => $val) {
            $presetStepData[$key]['progress_id']    = $progressResult->id;
            $presetStepData[$key]['index']          = $key;
            $presetStepData[$key]['preset_step_id'] = $val;
            $presetStepData[$key]['created_at']     = date('Y-m-d H:i:s');
            $presetStepData[$key]['updated_at']     = date('Y-m-d H:i:s');
        }
        $progressStepsResult = ProgressSteps::insert($presetStepData);

        if ($progressResult == false || $progressStepsResult == false) {
            DB::rollBack();
            $result = false;
        } else {
            DB::commit();
            $result = $progressResult->id;
        }

        return $result;
    }

}
