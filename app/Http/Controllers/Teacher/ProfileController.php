<?php
/**
 * 这个是教师自己管理自己的档案的controller
 */
namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\MyStandardRequest;
use App\Models\Teachers\Teacher;
use App\Dao\Schools\OrganizationDao;
use App\Models\Schools\Organization;
use App\Dao\Schools\SchoolDao;
use App\Dao\Teachers\QualificationDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\User;
use App\Utils\FlashMessageBuilder;
use Illuminate\Support\Facades\Auth;

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

    /**
     * 显示编辑教师档案的表单
     * @param MyStandardRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function modify(MyStandardRequest $request){
        $profileDao = new TeacherProfileDao();

        if($request->method()==='GET'){
            if($request->uuid()){
                $profile = $profileDao->getTeacherProfileByTeacherIdOrUuid($request->uuid());
            }
            else{
                $profile = $request->user()->profile;
            }

            $this->dataForView['profile'] = $profile;
            $this->dataForView['pageTitle'] = '编辑用户档案';
            return view('teacher.profile.edit_profile', $this->dataForView);
        }
        elseif ($request->method()==='POST'){
            $profileData = $request->get('profile');
            $teacherData = $request->get('teacher');

            $profile = $profileDao->getTeacherProfileByTeacherIdOrUuid($profileData['uuid']);
            foreach ($profileData as $field=>$value) {
                $profile->$field = $value;
            }
            $profile->save();

            $user = $profile->user;
            foreach ($teacherData as $k=>$v) {
                $user->$k = $v;
            }
            $user->save();

            FlashMessageBuilder::Push($request, 'success',$user->name.'档案数据更新成功');
            /**
             * @var User $operator
             */
            $operator = Auth::user();

            if($operator->isSchoolAdminOrAbove()){
                return redirect()->route('school_manager.teachers.edit-profile',['uuid'=>$user->id]);
            }else{
                return redirect()->route('teacher.profile.edit',['uuid'=>$profileData['uuid']]);
            }

        }
    }
}
