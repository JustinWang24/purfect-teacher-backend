<?php


namespace App\Dao\Users;


use App\Models\Users\UserCodeRecord;
use App\Utils\Misc\ConfigurationTool;

class UserCodeRecordDao
{

    public function create($data) {
        return UserCodeRecord::create($data);
    }


    /**
     * 二维码使用记录
     * @param $schoolId
     * @return mixed
     */
    public function getCodeRecordBySchoolId($schoolId) {
        $map = ['school_id'=>$schoolId];
        return UserCodeRecord::where($map)
            ->orderBy('created_at','desc')
            ->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }
}
