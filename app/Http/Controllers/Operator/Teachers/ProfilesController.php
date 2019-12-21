<?php

namespace App\Http\Controllers\Operator\Teachers;

use App\Dao\Performance\TeacherPerformanceDao;
use App\Dao\Schools\OrganizationDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Users\UserDao;
use App\Http\Requests\MyStandardRequest;
use App\Http\Controllers\Controller;
use App\Models\Schools\Organization;
use App\Models\Teachers\Teacher;
use App\Models\Users\UserOrganization;
use App\Utils\FlashMessageBuilder;

class ProfilesController extends Controller
{
    public function edit(MyStandardRequest $request){
        $this->dataForView['pageTitle'] = '教师档案管理';
        $schoolId = session('school.id');
        $id = $request->uuid();
        $dao = new UserDao();
        /**
         * @var Teacher $teacher
         */
        $teacher = $dao->getTeacherByIdOrUuid($id);
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
        $this->dataForView['configs'] = $school->teacherPerformanceConfigs;
        $this->dataForView['history'] = $teacher->performances ?? [];
        return view('teacher.profile.edit', $this->dataForView);
    }

    /**
     * 教师年终考评
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function manage_performance(MyStandardRequest $request){
        $schoolDao = new SchoolDao();
        $school = $schoolDao->getSchoolById(session('school.id'));
        $this->dataForView['configs'] = $school->teacherPerformanceConfigs;

        $dao = new UserDao();
        $teacher = $dao->getTeacherByUuid($request->uuid());
        $this->dataForView['teacher'] = $teacher;

        return view('teacher.profile.manage_performance', $this->dataForView);
    }

    /**
     * 保存
     * @param MyStandardRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function manage_performance_save(MyStandardRequest $request){
        $data = $request->all();
        $dao = new TeacherPerformanceDao($request->session()->get('school.id'));
        $result = $dao->create($data['performance'], $data['items'], $request->user());

        if($result->isSuccess()){
            FlashMessageBuilder::Push($request, 'success','年终评估已经保存');
        }
        else{
            FlashMessageBuilder::Push($request, 'error',$result->getMessage());
        }
        return redirect()->route('school_manager.teachers.edit-profile',['uuid'=>$data['performance']['user_id']]);
    }
}
