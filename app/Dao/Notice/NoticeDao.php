<?php

namespace App\Dao\Notice;

use App\Models\Notices\Notice;
use App\Utils\Misc\ConfigurationTool;

class NoticeDao
{

    /**
     * 根据学校ID 获取通知
     * @param $where
     * @return mixed
     */
    public function getNoticeBySchoolId($where)
    {
        return Notice::where($where)->paginate(ConfigurationTool::DEFAULT_PAGE_SIZE);
    }


}
