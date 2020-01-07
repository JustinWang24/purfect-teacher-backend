<?php

namespace App\Exports;

use App\Dao\OA\OaAttendanceTeacherDao;
use App\Models\Teachers\TeacherProfile;
use Maatwebsite\Excel\Concerns\FromCollection;

class AttendanceTotalExport implements FromCollection
{
    private $schoolId;
    private $current;
    function __construct($schoolId, $current)
    {
        $this->schoolId = $schoolId;
        $this->current = $current;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $dao = new OaAttendanceTeacherDao();
        $userList = $dao->getUserList($this->schoolId, 'week', $this->current);
        $cellData = [];
        foreach($userList as $k => $user) {

            $cellData[$k+1]['name'] = $user->user->name;
            $cellData[$k+1]['category'] = $user->user->profile->category_major;
            $cellData[$k+1]['total'] = $dao->countCourseNumByUser($this->schoolId, $user->user_id, 'week', $this->current);
        }
        array_unshift($cellData, ['姓名','专业分类','课时统计']);
        return collect($cellData);
    }
}
