<?php

namespace App\Http\Controllers\Api\Recruitment;

use App\Dao\RecruitStudent\RegistrationInformaticsDao;
use App\Dao\Schools\GradeDao;
use App\Dao\Schools\MajorDao;
use App\Dao\Users\GradeUserDao;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecruitStudent\EmployRequest;
use App\Utils\JsonBuilder;
use App\Models\Acl\Role;

class EmployController extends Controller
{

    /***
     * 获取所有未分配的学生
     * @param EmployRequest $request
     * @return string
     */
    public function index(EmployRequest $request)
    {
//        $schoolId = $request->getSchoolId();
        $schoolId       = 7;
        $informaticsDao = new RegistrationInformaticsDao;
        $gradeUserDao   = new GradeUserDao;

        $unassignedData = $informaticsDao->unassigned($schoolId); // 未分配班级的数据
        $gradeUser      = $gradeUserDao->getAllStudentBySchoolId($schoolId);

        $gradeUserId = array_column($gradeUser->toArray(), 'user_id');
        $users       = []; // 未分配班级的学生
        foreach ($unassignedData as $key => $value) {
            if (!in_array($value['user_id'], $gradeUserId)) {
                $users[$key]['major_id'] = $value['major_id'];
                $users[$key]['user_id']  = $value['user_id'];
                $users[$key]['name']     = $value['name'];
            }
        }

        return JsonBuilder::Success($users);
    }

    /**
     * 分配班级
     * @param EmployRequest $request
     * @return string
     */
    public function distribution(EmployRequest $request)
    {
        $data = $request->all();

        $dao      = new GradeUserDao;
        $gradeDao = new GradeDao;
        $majorDao = new MajorDao;
        $grade    = $gradeDao->getGradeById($data[0]['grade_id']);

        $major = $majorDao->getMajorById($grade->major_id);

        foreach ($data as $key => $val ) {
            $data[$key]['school_id']     = $major['school_id'];
            $data[$key]['campus_id']     = $major['campus_id'];
            $data[$key]['institute_id']  = $major['institute_id'];
            $data[$key]['department_id'] = $major['department_id'];
            $data[$key]['user_type']     = Role::REGISTERED_USER;
        }

        $result  = $dao->addGradUser($data);
        if ($result) {
           return JsonBuilder::Success('分配班级成功');
        } else {
           return JsonBuilder::Error('分配班级失败');
        }
    }


}
