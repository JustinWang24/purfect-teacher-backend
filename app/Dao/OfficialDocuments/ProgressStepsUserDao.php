<?php

namespace App\Dao\OfficialDocuments;

use App\Models\OfficialDocument\ProgressStepsUser;

class ProgressStepsUserDao
{

    /**
     * 添加步骤负责人
     * @param $data
     * @return mixed
     */
    public function createStep($data)
    {
        return ProgressStepsUser::create($data);
    }

    /**
     * 修改步骤负责人
     * @param $data
     * @param $where
     * @return mixed
     */
    public function updateStep($data, $where)
    {
         return ProgressStepsUser::where('id', $where)->update($data);
    }

}
