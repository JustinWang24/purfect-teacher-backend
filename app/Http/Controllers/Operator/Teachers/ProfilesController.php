<?php

namespace App\Http\Controllers\Operator\Teachers;

use App\Dao\Performance\TeacherPerformanceDao;
use App\Dao\Schools\CampusDao;
use App\Dao\Schools\InstituteDao;
use App\Dao\Schools\OrganizationDao;
use App\Dao\Schools\SchoolDao;
use App\Dao\Teachers\TeacherProfileDao;
use App\Dao\Users\GradeUserDao;
use App\Dao\Users\UserDao;
use App\Http\Requests\MyStandardRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\NetworkDisk\MediaRequest;
use App\Models\Acl\Role;
use App\Models\NetworkDisk\Media;
use App\Models\Schools\Organization;
use App\Models\Teachers\Teacher;
use App\User;
use App\Utils\FlashMessageBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class ProfilesController extends Controller
{
    public function save(MyStandardRequest $request){
        DB::beginTransaction();
        try{
            $teacherData = $request->get('teacher');
            $profileData = $request->get('profile');

            $teacherData['uuid'] = Uuid::uuid4()->toString();
            $teacherData['api_token'] = Uuid::uuid4()->toString();
            $teacherData['type'] = Role::TEACHER;
            $teacherData['status'] = User::STATUS_VERIFIED;
            $pwd = substr($profileData['id_number'],-6); // 身份证的后六位
            $teacherData['password'] = Hash::make($pwd);

            $userDao = new UserDao();
            $user = $userDao->createUser($teacherData);

            $profileDao = new TeacherProfileDao();
            $profileData['user_id'] = $user->id;
            $profileData['uuid'] = Uuid::uuid4()->toString();
            $profileDao->createProfile($profileData);

            $gGao = new GradeUserDao();
            $firstCampus = (new CampusDao())->getCampusesBySchool(session('school.id'))[0];
            $firstInstitute = (new InstituteDao())->getBySchool(session('school.id'))[0];
            $gGao->create([
                'user_id'=>$user->id,
                'name'=>$user->name,
                'user_type'=>Role::TEACHER,
                'school_id'=>session('school.id'),
                'campus_id'=>$firstCampus->id,
                'institute_id'=>$firstInstitute->id,
                'department_id'=>0,
                'grade_id'=>0,
                'last_updated_by'=>Auth::user()->id,
            ]);

            DB::commit();
            FlashMessageBuilder::Push(
                $request,
                'success',
                '教师档案保存成功, 登陆用户名: '.$user->mobile.', 登陆密码: '.$pwd.'(即身份证的后 6 位)');
        }
        catch (\Exception $exception){
            DB::rollBack();
            FlashMessageBuilder::Push(
                $request,
                'error',
                $exception->getMessage());
        }

        return redirect()->route('school_manager.school.teachers');
    }

    public function add_new(MyStandardRequest $request){
        $this->dataForView['pageTitle'] = '教师档案管理';
        $schoolId = session('school.id');
        $this->dataForView['school_id'] = $schoolId;
        return view('teacher.profile.add_new', $this->dataForView);
    }

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

    /**
     * 教职工的档案照片管理
     * @param MediaRequest $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function avatar(MediaRequest $request){
        if($request->method() === 'GET'){
            $this->dataForView['pageTitle'] = '教职工档案照片';
            $this->dataForView['user'] = (new UserDao())->getTeacherByIdOrUuid($request->uuid());
            return view('teacher.profile.update_avatar', $this->dataForView);
        }
        elseif ($request->method() === 'POST'){
            $user = (new UserDao())->getTeacherByIdOrUuid($request->get('user')['id']);
            $file = $request->getFile();
            $path = Media::DEFAULT_UPLOAD_PATH_PREFIX.$user->id;
            $url = $file->storeAs($path, Str::random(10).'.'.$file->getClientOriginalExtension()); // 上传并返回路径
            $profile = $user->profile;
            $profile->avatar = str_replace('public/','storage/',$url);
            $profile->save();
            FlashMessageBuilder::Push($request, 'success','照片已更新');
            return redirect()->route('school_manager.teachers.edit-avatar',['uuid'=>$user->id]);
        }
    }
}
