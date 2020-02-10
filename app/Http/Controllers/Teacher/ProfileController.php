<?php
/**
 * 这个是教师自己管理自己的档案的controller
 */
namespace App\Http\Controllers\Teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Dao\Users\UserDao;
use App\Models\Teachers\Teacher;
use App\Dao\Schools\OrganizationDao;
use App\Models\Schools\Organization;
use App\Dao\Schools\SchoolDao;
use App\Dao\Teachers\QualificationDao;

class ProfileController extends Controller
{
    public function edit(MyStandardRequest $request){
        $this->dataForView['pageTitle'] = '教师档案管理';

        /**
         * @var Teacher $teacher
         */
        $teacher = $request->user();
        $schoolId = $teacher->getSchoolId();

        $this->dataForView['teacher'] = $teacher;
        $this->dataForView['userOrganization'] = Teacher::myUserOrganization($teacher->id);
        $this->dataForView['profile'] = $teacher->profile;
        // 行政方面的职务
        $this->dataForView['organizations'] = (new OrganizationDao())->getBySchoolId($schoolId);
        $this->dataForView['titles'] = Organization::AllTitles();

        // 教学方面的职务: 是否隶属于任何的教研组
        $this->dataForView['groups'] = Teacher::myTeachingAndResearchGroup($teacher->id);
        // 学生管理方面的职务: 是否班主任
        $this->dataForView['gradeManager'] = Teacher::myGradeManger($teacher->id);
        $this->dataForView['yearManager'] = Teacher::myYearManger($teacher->id);

        // 该教师历年的考核记录
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById($schoolId);
        $this->dataForView['school'] = $school;
        $this->dataForView['configs'] = $school->teacherPerformanceConfigs;
        $this->dataForView['history'] = $teacher->performances ?? [];

        // 该教师的评聘佐证材料
        $qualificationDao =  new QualificationDao;
        $qualification = $qualificationDao->getTeacherQualificationByTeacherId($teacher->id);
        $this->dataForView['qualification'] = $qualification;
        return view('teacher.profile.edit', $this->dataForView);
    }
}
