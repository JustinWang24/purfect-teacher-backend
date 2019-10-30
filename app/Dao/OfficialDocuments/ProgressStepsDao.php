<?php


namespace App\Dao\OfficialDocuments;

use App\Models\OfficialDocument\ProgressSteps;

class ProgressStepsDao
{

    /**
     * 根据流程ID 获取一条公文流程的详情(每一步)
     */
    public function getOneProgressDetailsByProgressId()
    {

        $data = ProgressSteps::where('progress_id', 1)->select('id', 'preset_step_id', 'index')->with([
            'presetStep' => function($query){
               $query->select('id','name', 'describe');
            },
            'users' => function($query){
                // 负责步骤的审核人 抄送人
            }
            ])->get();

        dd($data->toArray());

    }


}
