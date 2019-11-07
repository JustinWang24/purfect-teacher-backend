<?php

namespace App\Dao\Section;

use App\Models\Sections\Section;

class SectionDao
{

    /**
     * 根据学校ID获取所有部门联系电话
     * @param $schoolId
     * @return array
     */
    public function getSectionMobileBySchoolId($schoolId)
    {
        $data =  Section::where('school_id', $schoolId)->select('name', 'mobile')->get();
        $result  = [];
        foreach ($data as $key => $val) {
            $result[$key]['name'] = $val['name'];
            $result[$key]['tel']  = $val['mobile'];
        }
        return ['department_list' => $result];
    }

}
