<?php

namespace App\Dao\OA;

use App\Models\OA\WorkLogRead;

class WorkLogReadDao
{

    /**
     * 已读
     * @param $workId
     * @param $userId
     */
    public function create($workId, $userId)
    {
        $result = WorkLogRead::where('work_id', $workId)->first();
        if (empty($result)) {
            WorkLogRead::create(['work_id'=> $workId, 'user_id' => $userId]);
        }
    }

}
