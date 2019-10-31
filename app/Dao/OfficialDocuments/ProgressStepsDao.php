<?php

namespace App\Dao\OfficialDocuments;

use App\Models\OfficialDocument\ProgressSteps;

class ProgressStepsDao
{

    /**
     * 根据流程ID 获取一条公文流程的详情(每一步)
     * @param $progressId
     * @return int
     */
    public function getOneProgressDetailsByProgressId($progressId)
    {
        $data = ProgressSteps::where('progress_id', $progressId)->select('id', 'preset_step_id', 'index')->with([
            'presetStep' => function($query) {  // 流程中的步骤
               $query->select('id', 'name', 'describe');
            },
            'progressStepsUser' => function($query) {   // 负责步骤的审核人 抄送人
               $query->select('progress_steps_id', 'progress_steps_users.type', 'name')
                     ->join('users', 'users.id', '=' , 'progress_steps_users.user_id');
            }
            ])->orderBy('index', 'asc')->get();

        return $data;
    }





}
